<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;

final class AssetManifest
{
    public static function vite(string|array $entrypoints): string
    {
        $entrypoints = self::normalizeEntrypoints($entrypoints);

        if ($entrypoints === []) {
            return '';
        }

        $hotPath = public_path('hot');

        if (file_exists($hotPath)) {
            return self::renderHot($hotPath, $entrypoints);
        }

        $manifest = self::readManifest();

        if ($manifest === []) {
            return '';
        }

        $tags = self::renderBuildTags($manifest, $entrypoints);

        return implode("\n", $tags);
    }

    private static function readManifest(): array
    {
        $manifestPath = public_path('build/manifest.json');

        if (! file_exists($manifestPath)) {
            return [];
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        return is_array($manifest) ? $manifest : [];
    }

    private static function renderBuildTags(array $manifest, array $entrypoints): array
    {
        foreach ($entrypoints as $entrypoint) {
            if (! isset($manifest[$entrypoint]) || ! is_array($manifest[$entrypoint])) {
                throw new InvalidArgumentException("Unable to locate file in Vite manifest: {$entrypoint}.");
            }
        }

        $chunks = collect($entrypoints)
            ->map(fn (string $entrypoint) => $manifest[$entrypoint])
            ->values();

        $styles = $chunks
            ->flatMap(function (array $chunk) {
                return collect([$chunk['file'] ?? null])
                    ->merge($chunk['css'] ?? []);
            })
            ->filter(fn ($file) => is_string($file) && str_ends_with($file, '.css'))
            ->unique()
            ->map(fn (string $file) => '<link rel="stylesheet" href="' . asset('build/' . $file) . '">')
            ->values();

        $scripts = $chunks
            ->map(fn (array $chunk) => $chunk['file'] ?? null)
            ->filter(fn ($file) => is_string($file) && str_ends_with($file, '.js'))
            ->unique()
            ->map(fn (string $file) => '<script type="module" src="' . asset('build/' . $file) . '"></script>')
            ->values();

        return $styles->concat($scripts)->all();
    }

    private static function renderHot(string $hotPath, array $entrypoints): string
    {
        $hotUrl = trim((string) file_get_contents($hotPath));

        if ($hotUrl === '') {
            return '';
        }

        $hotUrl = rtrim($hotUrl, '/');

        $tags = collect($entrypoints)
            ->map(fn (string $entrypoint) => ltrim($entrypoint, '/'))
            ->filter()
            ->map(function (string $entrypoint) use ($hotUrl) {
                if (str_ends_with($entrypoint, '.css')) {
                    return '<link rel="stylesheet" href="' . $hotUrl . '/' . $entrypoint . '">';
                }

                return '<script type="module" src="' . $hotUrl . '/' . $entrypoint . '"></script>';
            });

        $tags = collect(['<script type="module" src="' . $hotUrl . '/@vite/client"></script>'])
            ->concat($tags)
            ->all();

        return implode("\n", $tags);
    }

    private static function normalizeEntrypoints(string|array $entrypoints): array
    {
        return collect(is_string($entrypoints) ? [$entrypoints] : $entrypoints)
            ->filter(fn ($entrypoint) => is_string($entrypoint) && trim($entrypoint) !== '')
            ->map(fn (string $entrypoint) => trim($entrypoint))
            ->unique()
            ->values()
            ->all();
    }
}

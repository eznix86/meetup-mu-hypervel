<?php

declare(strict_types=1);

namespace App\Providers;

use Hypervel\Support\Facades\Blade;
use Hypervel\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::directive('vite', function ($expression) {
            $entrypoints = $expression ?: '[]';

            return "<?php echo \\App\\Support\\AssetManifest::vite({$entrypoints}); ?>";
        });
    }

    public function register(): void
    {
    }
}

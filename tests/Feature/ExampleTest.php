<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends TestCase
{
    public function testTheApplicationReturnsSuccessfulResponse()
    {
        $html = view('index', [
            'meetups' => collect(),
            'todays' => collect(),
        ])->render();

        $this->assertStringContainsString('Mauritius Tech Meetups', $html);
    }
}

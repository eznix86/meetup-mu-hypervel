<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Meetup;
use App\Models\User;
use Hypervel\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Meetup::factory(8)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

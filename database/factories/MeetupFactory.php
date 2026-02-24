<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Meetup;
use Carbon\Carbon;
use Hypervel\Database\Eloquent\Factories\Factory;

/**
 * @extends \Hypervel\Database\Eloquent\Factories\Factory<\App\Models\Meetup>
 */
class MeetupFactory extends Factory
{
    protected $model = Meetup::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['Talk', 'Workshop', 'Networking']),
            'community' => fake()->randomElement(['Python Mauritius', 'GDG Mauritius', 'Laravel MU']),
            'title' => fake()->sentence(4),
            'abstract' => fake()->paragraph(),
            'location' => fake()->company(),
            'registration' => fake()->url(),
            'date' => Carbon::now()->addDays(fake()->numberBetween(0, 30)),
            'capacity' => fake()->numberBetween(30, 200),
        ];
    }
}

<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = Carbon::today()->addHours(fake()->numberBetween(8, 12)); // Start between 8 AM and 12 PM
        $endTime = $startTime->addHours(fake()->numberBetween(1, 8)); // End 1-8 hours later
        $lunchingTime = fake()->boolean()
            ? fake()->randomElement(['00:30:00', '01:00:00'])
            : null;

        return [
            'project_id' => \App\Models\Project::factory(),
            'start_time' => $startTime->toDateTimeString(),
            'end_time' => $endTime->toDateTimeString(),
            'lunching_time' => $lunchingTime,
            'price_per_hour' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}

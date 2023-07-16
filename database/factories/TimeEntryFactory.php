<?php

namespace Database\Factories;

use DateTime;
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
        [$start, $stop] = $this->randomStartStop();

        return [
            'description' => $this->faker->sentence,
            'start' => $start,
            'stop' => $stop,
            'billable' => $this->faker->boolean,
        ];
    }

    private function randomStartStop(): array
    {
        $start = $this->faker->dateTimeBetween('-1 day')->format(DateTime::ATOM);
        $stop = $this->faker->dateTimeBetween($start, '+1 hour')->format(DateTime::ATOM);

        return [
            $start,
            $stop
        ];
    }
}
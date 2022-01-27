<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleEventTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(),
            'minute' => $this->faker->numberBetween(0, 60),
            'hour' => $this->faker->numberBetween(0, 12),
            'day_of_month' => '*',
            'month' => '*',
            'day_of_week' => $this->faker->numberBetween(0, 6),
            'first_of_month' => config('enums.YES')
        ];
    }
}

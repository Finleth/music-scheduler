<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CalendarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'time_tree_calendar_id' => '',
            'name' => $this->faker->name(),
            'status' => config('enums.status.ACTIVE')
        ];
    }
}

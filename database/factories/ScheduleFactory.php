<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tomorrow = new DateTime('tomorrow');

        return [
            'event_date' => $tomorrow
        ];
    }
}

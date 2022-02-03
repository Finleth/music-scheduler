<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class MusicianBlackoutFactory extends Factory
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
            'start' => $this->faker->date('now'),
            'end' => $this->faker->date($tomorrow->format(config('app.DATE_FORMAT')))
        ];
    }
}

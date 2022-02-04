<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MusicianInstrumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'primary' => config('enums.NO')
        ];
    }

    /**
     * Indicate the instrument is the user's primary instrument
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
    */
    public function primary()
    {
        return $this->state(function () {
            return ['primary' => config('enums.YES')];
        });
    }
}

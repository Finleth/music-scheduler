<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MusicianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'status' => config('enums.status.ACTIVE')
        ];
    }
}

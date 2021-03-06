<?php

namespace Database\Factories;

use App\Models\ScheduleEventType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MusicianScheduleEventTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'frequency' => $this->faker->numberBetween(1, 100),
            'auto_schedule' => config('enums.YES')
        ];
    }
}

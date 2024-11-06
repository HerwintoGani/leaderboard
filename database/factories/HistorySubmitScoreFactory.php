<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistorySubmitScore>
 */
class HistorySubmitScoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'level' => $this->faker->randomDigit(),
            'score' => $this->faker->randomNumber(7, false),
            'user_id' => $this->faker->numberBetween(1, 10000)
        ];
    }
}

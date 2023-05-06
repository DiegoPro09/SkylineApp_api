<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Score>
 */
class ScoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cld_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'user_id' => $this->faker->randomElement([1]),
            'assignament_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'first_score' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'second_score' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'average' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'year' => $this->faker->randomElement([2023]),
        ];
    }
}

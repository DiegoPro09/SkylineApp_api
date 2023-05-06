<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Behavior>
 */
class BehaviorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
            'f_justify' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'f_injustify' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'sanctions' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'observations' => $this->faker->realText($maxNbChars = 10, $indexSize = 2)
        ];
    }
}

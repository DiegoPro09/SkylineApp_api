<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseLevelDivision>
 */
class CourseLevelDivisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'course_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'level_id' => $this->faker->randomElement([1]),
            'division_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'year' => $this->faker->randomElement([2023]),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName($gender = 'male'),
            'last_name' => $this->faker->lastName(),
            'dni' => $this->faker->postcode(),
            'phone' => $this->faker->tollFreePhoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'question' => $this->faker->realText($maxNbChars = 20, $indexSize = 2),
            'answer' => $this->faker->realText($maxNbChars = 10, $indexSize = 2),
            'remember_token' => Str::random(10),
            'state' => $this->faker->randomElement([1]),
            'role_id' => $this->faker->randomElement([5])
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

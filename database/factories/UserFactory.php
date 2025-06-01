<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $maximum_streak_days = fake()->numberBetween(0,500);
        return [
            'user_handle' => Str::slug($name) . '-' . Str::random(6),
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => fake()->password(),
            'remember_token' => Str::random(10),
            'role' => 'user',
            'solved_problems' => fake()->numberBetween(0,1500),
            'last_active_at' => now(),
            'previous_active_at'=> fake()->dateTimeBetween('-1 year','now'),
            'current_streak'=> 0,
            'max_streak' => $maximum_streak_days,
            'social_score' => fake()->numberBetween(0,100),
            'technical_score' => fake()->numberBetween(0,100),
            'linkedin_url' => fake()->url(),
            'github_url' => fake()->url(),
            'portfolio_url' => fake()->url(),
        ];

    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

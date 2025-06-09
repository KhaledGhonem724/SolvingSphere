<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition()
    {
        $owner = User::factory()->create();
        
        return [
            'name' => $this->faker->company(),
            'short_name' => $this->faker->unique()->slug(2),
            'description' => $this->faker->paragraph(),
            'visibility' => $this->faker->randomElement(['public', 'private']),
            'join_policy' => $this->faker->randomElement(['free', 'apply_approve', 'invite_only']),
            'max_members' => $this->faker->numberBetween(10, 100),
            'owner_id' => $owner->user_handle,
        ];
    }

    public function public()
    {
        return $this->state(function (array $attributes) {
            return [
                'visibility' => 'public',
                'join_policy' => 'free',
            ];
        });
    }

    public function private()
    {
        return $this->state(function (array $attributes) {
            return [
                'visibility' => 'private',
                'join_policy' => 'apply_approve',
            ];
        });
    }

    public function inviteOnly()
    {
        return $this->state(function (array $attributes) {
            return [
                'visibility' => 'private',
                'join_policy' => 'invite_only',
            ];
        });
    }
} 
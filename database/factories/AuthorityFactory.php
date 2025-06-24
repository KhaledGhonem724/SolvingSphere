<?php

namespace Database\Factories;

use App\Models\Authority;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorityFactory extends Factory
{
    protected $model = Authority::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
        ];
    }
}

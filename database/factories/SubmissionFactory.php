<?php

namespace Database\Factories;

use App\Models\Submission;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->paragraphs(3, true),
            'language' => $this->faker->randomElement(['cpp', 'java', 'python']),
            'oj_response' => $this->faker->randomElement(['Accepted', 'Wrong Answer', 'Time Limit Exceeded']),
            'result' => $this->faker->randomElement(['failed', 'succeeded']),
            'original_link' => $this->faker->optional()->url(),
            'ai_response' => $this->faker->optional()->sentence(),
            'owner_id' => User::inRandomOrder()->first()?->user_handle ?? 'default_user',
            'problem_id' => Problem::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}

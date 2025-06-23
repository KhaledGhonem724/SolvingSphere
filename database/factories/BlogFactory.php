<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

use App\Enums\VisibilityStatus;

class BlogFactory extends Factory
{
    protected $model = \App\Models\Blog::class;

    public function definition()
    {
        // Pick a random existing user owner_id (assuming user_handle is primary key or unique)
        $owner = User::inRandomOrder()->first();

        return [
            'title' => $this->faker->unique()->sentence(6),
            'content' => implode("\n\n", [
                '# ' . $this->faker->sentence,
                $this->faker->paragraph,
                '## Subheading',
                "- " . $this->faker->sentence,
                "- " . $this->faker->sentence,
                "\n```php\n" . 'echo "Hello World!";' . "\n```",
                $this->faker->paragraph,
            ]),
            'blog_type' => $this->faker->randomElement(['discussion', 'question', 'explain']),
            'score' => $this->faker->numberBetween(0, 100),
            'owner_id' => $owner ? $owner->user_handle : 'default_handle', // fallback if no users yet
            'status' => $this->faker->randomElement(VisibilityStatus::cases())
        ];

    }
}

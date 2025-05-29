<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;

class BlogSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run()
    {
        // Create 20 sample blogs
        Blog::factory()->count(20)->create();
    }
}

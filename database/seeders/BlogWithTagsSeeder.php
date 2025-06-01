<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Blog;
use App\Models\Tag;

class BlogWithTagsSeeder extends Seeder
{
    public function run(): void
    {
         // Create some tags
         $tags = \App\Models\Tag::all();

        \App\Models\Blog::all()->each(function ($blog) use ($tags) {
        $blog->tags()->sync(
            $tags->random(rand(1, 5))->pluck('id')->toArray()
        );
    });
    }
}

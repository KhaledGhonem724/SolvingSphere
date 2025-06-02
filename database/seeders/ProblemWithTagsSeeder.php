<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Problem;
use App\Models\Tag;

class ProblemWithTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create some tags
         $tags = \App\Models\Tag::all();

        \App\Models\Problem::all()->each(function ($problem) use ($tags) {
            $problem->tags()->sync(
                $tags->random(rand(1, 5))->pluck('id')->toArray()
            );
        });
    }
}

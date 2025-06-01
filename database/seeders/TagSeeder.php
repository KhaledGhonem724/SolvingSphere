<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        Tag::factory()->count(10)->create();

        $presetTags = ['algorithms', 'data structures', 'graphs', 'greedy', 'dp', 'math', 'geometry'];

        foreach ($presetTags as $tagName) {
            Tag::firstOrCreate(['name' => $tagName]);
        }
    }
}

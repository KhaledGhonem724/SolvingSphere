<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Problem;

class ProblemSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create 10 problems using the factory
        Problem::factory()->count(10)->create();
    }
}



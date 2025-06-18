<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndAuthoritySeeder::class,
            UserSeeder::class,
            ProblemSeeder::class,
            BlogSeeder::class,
            TagSeeder::class,
            BlogWithTagsSeeder::class,
            ProblemWithTagsSeeder::class,
            SubmissionSeeder::class,
            ReportSeeder::class,
        ]);
    }
}

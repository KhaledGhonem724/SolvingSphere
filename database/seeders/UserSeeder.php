<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'user_handle' => "khaledghonem724",
            'name' => "khaled ghonem",
            'email' => "khaledghonem724@gmail.com",
            'email_verified_at' => now(),
            'password' => "z5wXWs4imSEFtJ5",
            'remember_token' => "z5wXWs4imSEFtJ5",
            'status' => 'admin',
            'role_id' => 1,
            'solved_problems' => 99,
            'last_active_at' => now(),
            'previous_active_at'=> now(),
            'current_streak'=> 0,
            'max_streak' => 99,
            'social_score' => 99,
            'technical_score' => 99,
            'linkedin_url' => "https://www.linkedin.com/in/khaled-ghonem-0b4023229/",
            'github_url' => "https://github.com/KhaledGhonem724",
            'portfolio_url' => "https://github.com/KhaledGhonem724",
        ]);
        User::factory()->count(30)->create();

    }
}

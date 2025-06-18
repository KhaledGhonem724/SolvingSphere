<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submission;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure problems and users exist before this
        Submission::factory()->count(50)->create();
    }
}

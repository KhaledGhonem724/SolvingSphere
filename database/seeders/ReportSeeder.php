<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Report;

class ReportSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        Report::factory()->count(15)->create();
    }
}

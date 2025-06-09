<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use App\Enums\ReportType;
use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // or an existing user handle
            'type' => $this->faker->randomElement(ReportType::cases()),
            'message' => $this->faker->optional()->paragraph,
            'status' => $this->faker->randomElement(ReportStatus::cases()),
            'assignee_id' => null, // or set a handle if needed
            'reportable_type' => null,
            'reportable_id' => null,
            'admin_notes' => $this->faker->optional()->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

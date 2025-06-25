<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Authority;
use App\Models\Report;
use App\Enums\TaskStatus;
use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        // Optional assignee
        $assignee = fake()->boolean(70) ? User::inRandomOrder()->first() : null;

        // Random status logic if assigned
        $status = $assignee
            ? fake()->randomElement([
                TaskStatus::Assigned,
                TaskStatus::Refused,
                TaskStatus::Solved,
                TaskStatus::Dismissed,
            ])
            : TaskStatus::Free;

        // 60% chance the task is NOT from a report
        $report = fake()->boolean(40) ? Report::inRandomOrder()->first() : null;

        return [
            'title'           => fake()->optional()->sentence,
            'user_id'         => $report
                ? $report->user_id
                : User::where('status', 'admin')->inRandomOrder()->first()?->user_handle,

            'authority_id'    => Authority::inRandomOrder()->first()?->id,
            'report_id'       => $report?->id,
            'type'            => fake()->randomElement(ReportType::cases())->value,
            'user_note'       => $report?->message ?? fake()->optional()->paragraph,
            'manager_note'    => fake()->optional()->paragraph,
            'admin_note'      => fake()->optional()->sentence,
            'assignee_id'     => $assignee?->user_handle,
            'status'          => $status,
            'reportable_type' => null,
            'reportable_id'   => null,
        ];
    }
}

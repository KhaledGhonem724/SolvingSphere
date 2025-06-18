<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Problem;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use App\Enums\ReportType;
use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        // List of reportable models
        $reportableModels = [
            Blog::class,
            null,
        ];
        // waiting for edits
        /*
            Comment::class,
            Problem::class,
        */
        // waiting for ahmed / anas 
        /* 
            Sheet::class,
            Topic::class,
            Roadmap::class,
            Group::class,
        */
        // Pick a random reportable model
        $reportableType = $this->faker->randomElement($reportableModels);
    
        // Pick a random existing ID from that model (null fallback if none exists)
        $reportableId = null;
        if($reportableType != null){
            $reportableId = $reportableType::inRandomOrder()->value('id');
        } 
    
        return [
            'user_id' => User::inRandomOrder()->value('user_handle') ?? User::factory(), // or just User::factory() if you prefer
            'type' => $this->faker->randomElement(ReportType::cases()),
            'message' => $this->faker->optional()->paragraph,
            'status' => $this->faker->randomElement(ReportStatus::cases()),
            'reportable_type' => $reportableId ? $reportableType : null,
            'reportable_id' => $reportableId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

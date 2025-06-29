<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'language',
        'result',
        'oj_response',
        'original_link',
        'ai_response',
        'owner_id',
        'problem_id',
    ];
    // $table->enum('language',['cpp', 'java', 'python'])->default('cpp');
    // $table->enum('result',['solved', 'attempted', 'todo'])->default('todo');

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'user_handle');
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class, 'problem_id');
    }
}

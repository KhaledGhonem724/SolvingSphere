<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Submiton extends Model
{
    protected $fillable = ['code', 'language', 'result', 'ai_help', 'ai_response', 'owner_id', 'problem_id'];

    public function user(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function problem(): BelongsTo { return $this->belongsTo(Problem::class, 'problem_id'); }
}
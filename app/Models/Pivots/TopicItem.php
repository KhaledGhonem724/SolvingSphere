<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopicItem extends Model
{
    protected $fillable = ['problem_id', 'external_link', 'topic_id'];

    public function problem(): BelongsTo { return $this->belongsTo(Problem::class, 'problem_id'); }
    public function topic(): BelongsTo { return $this->belongsTo(Topic::class, 'topic_id'); }
}
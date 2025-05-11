<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Solution extends Model
{
    protected $fillable = ['blog_id', 'problem_id'];

    public function blog(): BelongsTo { return $this->belongsTo(Blog::class, 'blog_id'); }
    public function problem(): BelongsTo { return $this->belongsTo(Problem::class, 'problem_id'); }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Tag extends Model
{
    protected $fillable = ['name'];

    public function problems(): BelongsToMany { 
        return $this->belongsToMany(Problem::class, 'problem_tag', 'tag_id', 'problem_id'); 
    }
    public function blogs(): BelongsToMany { 
        return $this->belongsToMany(Blog::class, 'blog_tag', 'tag_id', 'blog_id'); 
    }
}
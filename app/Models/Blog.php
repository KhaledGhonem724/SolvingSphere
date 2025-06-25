<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Enums\VisibilityStatus;

use App\Models\Pivots\BlogReaction;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'blog_type',
        'score',
        'owner_id',
        'status' 
    ];
    // admin related attribute
    protected $casts = [
        'status' => VisibilityStatus::class,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'user_handle');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'blog_id');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(BlogReaction::class, 'blog_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_tag', 'blog_id', 'tag_id');
    }


    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_blog', 'blog_id', 'group_id');
    }

    public function solutions(): HasMany
    {
        return $this->hasMany(Solution::class, 'blog_id');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}

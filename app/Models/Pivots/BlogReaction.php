<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogReaction extends Model
{
    protected $fillable = [
        'reaction',   // e.g., 'like', 'dislike', 'insightful'
        'owner_id',   // foreign key referencing users.user_handle
        'blog_id'     // foreign key referencing blogs.id
    ];

    /**
     * The user who made the reaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'user_handle');
    }

    /**
     * The blog post the reaction belongs to.
     */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
}

<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogReaction extends Model
{
    protected $fillable = [
        'value',   
        'user_id',   
        'blog_id'     
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_handle');
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}

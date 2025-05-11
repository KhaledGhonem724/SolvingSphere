<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class BlogReaction extends Model
{
    protected $fillable = ['reaction', 'owner_id', 'blog_id'];

    public function user(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function blog(): BelongsTo { return $this->belongsTo(Blog::class, 'blog_id'); }
}
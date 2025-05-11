<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = ['content', 'commenter_id', 'blog_id'];

    public function commenter(): BelongsTo { return $this->belongsTo(User::class, 'commenter_id'); }
    public function blog(): BelongsTo { return $this->belongsTo(Blog::class, 'blog_id'); }
    public function replies(): HasMany { return $this->hasMany(Reply::class, 'origin_comment_id'); }
}

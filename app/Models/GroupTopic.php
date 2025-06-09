<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'title',
        'content',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    // العلاقة مع المجموعة
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    // العلاقة مع المستخدم الذي أنشأ المناقشة
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // العلاقة مع الردود
    public function replies(): HasMany
    {
        return $this->hasMany(GroupTopicReply::class, 'topic_id');
    }
} 
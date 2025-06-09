<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMessage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'group_id',
        'user_id',
        'content',
        'is_pinned',
        'pinned_by',
        'read_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_pinned' => 'boolean',
        'read_by' => 'array',
        'pinned_at' => 'datetime',
    ];

    /**
     * Get the group that owns the message.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the user who created the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_handle');
    }

    /**
     * Get the user who pinned the message.
     */
    public function pinnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pinned_by', 'user_handle');
    }

    // العلاقة مع قراءات الرسالة
    public function reads(): HasMany
    {
        return $this->hasMany(GroupMessageRead::class, 'message_id');
    }

    public function markAsRead(User $user)
    {
        if (!in_array($user->user_handle, $this->read_by)) {
            $this->read_by = array_merge($this->read_by ?? [], [$user->user_handle]);
            $this->save();
        }
    }

    public function isReadBy(User $user): bool
    {
        return in_array($user->user_handle, $this->read_by ?? []);
    }
} 
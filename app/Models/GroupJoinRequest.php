<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupJoinRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'message',
        'status',
        'response'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_handle');
    }

    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        $this->save();

        // إضافة المستخدم كعضو في المجموعة
        $this->group->members()->attach($this->user_id, ['role' => 'member']);
    }

    public function reject($reason = null)
    {
        $this->status = self::STATUS_REJECTED;
        $this->response = $reason;
        $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
} 
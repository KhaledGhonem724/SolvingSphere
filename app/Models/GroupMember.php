<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMember extends Model
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
        'role',
    ];

    /**
     * Get the group that the member belongs to.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the user that is the member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_handle');
    }
} 
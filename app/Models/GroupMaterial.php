<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMaterial extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'group_id',
        'title',
        'description',
        'url',
        'type',
        'views_count',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'json',
        'last_viewed_at' => 'datetime',
    ];

    /**
     * Get the group that owns the material.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
} 
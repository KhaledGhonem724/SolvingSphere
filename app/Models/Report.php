<?php

namespace App\Models;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'status',
        'assignee_id',
        'reportable_type',
        'reportable_id',
        'admin_notes',
    ];

    protected $casts = [
        'type' => ReportType::class,
        'status' => ReportStatus::class,
    ];

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_handle');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id', 'user_handle');
    }

    // === SCOPES ===

    public function scopeUnresolved(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            ReportStatus::Resolved, 
            ReportStatus::Dropped,
        ]);
    }

    public function scopeByUser(Builder $query, string $userHandle): Builder
    {
        return $query->where('user_id', $userHandle);
    }

    public function scopeOfType(Builder $query, ReportType $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeOfStatus(Builder $query, ReportStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeAssignedTo(Builder $query, string $assigneeHandle): Builder
    {
        return $query->where('assignee_id', $assigneeHandle);
    }
}

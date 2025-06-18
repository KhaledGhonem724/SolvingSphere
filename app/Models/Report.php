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
    public const REPORT_TYPES = ['scientific', 'ethical', 'technical', 'other'];
    public const REPORTABLE_TYPES = ['blog','comment','problem','user','group','sheet','topic','roadmap'];

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'status',
        'reportable_type',
        'reportable_id',
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

    // === SCOPES ===

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

}

<?php
// app/Models/Task.php
namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'user_id', // reporter
        'authority_id',
        'report_id',
        'type',
        'user_note',
        'manager_note',
        'admin_note',
        'assignee_id',
        'status',
        'reportable_id',
        'reportable_type',
    ];

    protected $casts = [
        'status' => TaskStatus::class,

    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_handle');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id', 'user_handle');
    }

    public function authority(): BelongsTo
    {
        return $this->belongsTo(Authority::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
}

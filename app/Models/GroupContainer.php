namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GroupContainer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'group_id',
        'title',
        'description',
        'type',
        'settings',
        'items_count',
        'last_active_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'last_active_at' => 'datetime',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function content(): MorphTo
    {
        return $this->morphTo('content', 'type', 'id', 'container_id');
    }

    public function sheets()
    {
        return $this->hasMany(GroupSheet::class, 'container_id');
    }

    public function contests()
    {
        return $this->hasMany(GroupContest::class, 'container_id');
    }

    public function topics()
    {
        return $this->hasMany(GroupTopic::class, 'container_id');
    }
} 
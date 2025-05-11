<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralLog extends Model
{
    protected $fillable = ['action', 'actor_id', 'object', 'object_id', 'sent_at'];

    public function actor(): BelongsTo { return $this->belongsTo(User::class, 'actor_id'); }
}
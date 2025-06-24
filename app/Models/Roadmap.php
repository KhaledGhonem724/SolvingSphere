<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Roadmap extends Model
{
    protected $fillable = ['title', 'description', 'visibility', 'owner_id', 'share_token'];

    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'roadmap_topic', 'roadmap_id', 'topic_id')
            ->withPivot('order')
            ->withTimestamps()
            ->orderBy('pivot_order');
    }
}
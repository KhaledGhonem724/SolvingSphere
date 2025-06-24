<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Topic extends Model
{
    protected $fillable = ['title', 'description', 'visibility', 'owner_id' ,'share_token',];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function items(): HasMany
    {
        return $this->hasMany(TopicItem::class, 'topic_id');
    }
    
    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'topic_items', 'topic_id', 'problem_id')
            ->withPivot('external_link')
            ->withTimestamps();
    }


    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_topic', 'topic_id', 'group_id');
    }
    public function roadmaps(): BelongsToMany
    {
        return $this->belongsToMany(Roadmap::class, 'roadmap_topic', 'topic_id', 'roadmap_id');
    }
}

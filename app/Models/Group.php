<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = ['name', 'description', 'membership', 'owner_id'];

    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function members(): BelongsToMany { return $this->belongsToMany(User::class, 'group_member', 'group_id', 'user_id')->withPivot('role'); }
    public function sheets(): BelongsToMany { return $this->belongsToMany(Sheet::class, 'group_sheet', 'group_id', 'sheet_id'); }
    public function topics(): BelongsToMany { return $this->belongsToMany(Topic::class, 'group_topic', 'group_id', 'topic_id'); }
    public function blogs(): BelongsToMany { return $this->belongsToMany(Blog::class, 'group_blog', 'group_id', 'blog_id'); }
}
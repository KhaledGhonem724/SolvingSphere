<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupMember extends Model
{
    protected $table = 'group_member';

    protected $fillable = ['role', 'user_id', 'group_id'];

    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function group(): BelongsTo { return $this->belongsTo(Group::class, 'group_id'); }
}
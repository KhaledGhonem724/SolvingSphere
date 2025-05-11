<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Sheet extends Model
{
    protected $fillable = ['title', 'description', 'visibility', 'owner_id'];

    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function problems(): BelongsToMany { return $this->belongsToMany(Problem::class, 'sheet_problem', 'sheet_id', 'problem_id'); }
    public function groups(): BelongsToMany { return $this->belongsToMany(Group::class, 'group_sheet', 'sheet_id', 'group_id'); }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ExternalAccount extends Model
{
    protected $primaryKey = ['website', 'owner_id'];
    public $incrementing = false;
    protected $fillable = ['website', 'username', 'password', 'owner_id'];

    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
}
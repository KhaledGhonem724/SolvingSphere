<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'description'];

    public function authorities(): BelongsToMany
    {
        return $this->belongsToMany(Authority::class, 'role_authority');
    }


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}

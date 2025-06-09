<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $primaryKey = 'user_handle';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_handle',
        'name', 'email', 'password',
        'status', 'role_id',
        'linkedin_url', 'github_url', 'portfolio_url',
        'last_active_at', 'previous_active_at',
        'current_streak','max_streak','solved_problems',
        'social_score', 'technical_score',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_active_at' => 'datetime',
            'previous_active_at' => 'datetime',
        ];
    }

    
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasAuthority(string $authorityName): bool
    {
        return $this->role?->authorities->contains('name', $authorityName);
    }


    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
}

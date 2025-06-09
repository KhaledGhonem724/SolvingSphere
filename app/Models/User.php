<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_handle';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_handle',
        'name',
        'email',
        'password',
        'role',
        'solved_problems',
        'last_active_at',
        'previous_active_at',
        'current_streak',
        'max_streak',
        'social_score',
        'technical_score',
        'linkedin_url',
        'github_url',
        'portfolio_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_active_at' => 'datetime',
            'previous_active_at' => 'datetime',
        ];
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the groups owned by the user.
     */
    public function ownedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'owner_id', 'user_handle');
    }

    /**
     * Get the groups the user is a member of.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members', 'user_id', 'group_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the group messages created by the user.
     */
    public function groupMessages(): HasMany
    {
        return $this->hasMany(GroupMessage::class, 'user_id', 'user_handle');
    }

    /**
     * Get the group join requests made by the user.
     */
    public function groupJoinRequests(): HasMany
    {
        return $this->hasMany(GroupJoinRequest::class, 'user_id', 'user_handle');
    }
}

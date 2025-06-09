<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'short_name',
        'description',
        'visibility',
        'join_policy',
        'max_members',
        'owner_id',
    ];

    protected $casts = [
        'max_members' => 'integer',
    ];

    const ROLES = [
        'owner' => 'مالك المجموعة',
        'admin' => 'مشرف',
        'moderator' => 'مشرف محتوى',
        'member' => 'عضو'
    ];

    const ROLE_PERMISSIONS = [
        'owner' => ['manage_group', 'manage_members', 'manage_content', 'invite_members', 'remove_members', 'chat'],
        'admin' => ['manage_members', 'manage_content', 'invite_members', 'remove_members', 'chat'],
        'moderator' => ['manage_content', 'chat'],
        'member' => ['chat']
    ];

    /**
     * Get the owner of the group.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'user_handle');
    }

    /**
     * Get the members of the group.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id')
                    ->withPivot('role');
    }

    /**
     * Get the materials of the group.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(GroupMaterial::class);
    }

    /**
     * Get the messages in the group.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(GroupMessage::class);
    }

    /**
     * Get the join requests for the group.
     */
    public function joinRequests(): HasMany
    {
        return $this->hasMany(GroupJoinRequest::class);
    }

    public function sheets(): BelongsToMany { return $this->belongsToMany(Sheet::class, 'group_sheet', 'group_id', 'sheet_id'); }
    public function topics(): HasMany { return $this->hasMany(GroupTopic::class); }
    public function blogs(): BelongsToMany { return $this->belongsToMany(Blog::class, 'group_blog', 'group_id', 'blog_id'); }
    public function contests(): HasMany { return $this->hasMany(GroupContest::class); }

    public function canManageMembers(User $user): bool
    {
        if ($user->user_handle === $this->owner_id) {
            return true;
        }

        $member = $this->members()->where('users.user_handle', $user->user_handle)->first();
        if (!$member) {
            return false;
        }

        return in_array($member->pivot->role, ['owner', 'admin']);
    }

    public function isAdmin(User $user): bool
    {
        if ($user->user_handle === $this->owner_id) {
            return true;
        }

        $member = $this->members()->where('users.user_handle', $user->user_handle)->first();
        if (!$member) {
            return false;
        }

        return in_array($member->pivot->role, ['owner', 'admin']);
    }

    public function isModerator(User $user): bool
    {
        if ($user->user_handle === $this->owner_id) {
            return true;
        }

        $member = $this->members()->where('users.user_handle', $user->user_handle)->first();
        if (!$member) {
            return false;
        }

        return in_array($member->pivot->role, ['owner', 'admin', 'moderator']);
    }

    public function isMember(User $user): bool
    {
        if ($user->user_handle === $this->owner_id) {
            return true;
        }
        
        return $this->members()->where('users.user_handle', $user->user_handle)->exists();
    }

    public function canJoin(User $user): bool
    {
        if ($this->visibility === 'private' && $this->join_policy !== 'free') {
            return false;
        }

        if ($this->members()->count() >= $this->max_members) {
            return false;
        }

        return !$this->isMember($user);
    }

    public function getMemberRole(User $user): ?string
    {
        $member = $this->members()->where('user_id', $user->user_handle)->first();
        return $member ? $member->pivot->role : null;
    }

    public function canInviteMembers(User $user): bool
    {
        $role = $this->getMemberRole($user);
        return in_array($role, ['owner', 'admin', 'moderator']);
    }

    public function canRemoveMembers(User $user): bool
    {
        $role = $this->getMemberRole($user);
        return in_array($role, ['owner', 'admin']);
    }

    public function canManageContent(User $user): bool
    {
        $role = $this->getMemberRole($user);
        return in_array($role, ['owner', 'admin', 'moderator']);
    }

    public function getRolePermissions(string $role): array
    {
        return self::ROLE_PERMISSIONS[$role] ?? [];
    }
}
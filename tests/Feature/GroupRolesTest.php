<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupRolesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_owner_has_all_permissions()
    {
        $owner = User::factory()->create();
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'max_members' => 50,
        ]);

        $this->assertTrue($group->isAdmin($owner));
        $this->assertTrue($group->isModerator($owner));
        $this->assertTrue($group->canManageMembers($owner));
        $this->assertTrue($group->canManageContent($owner));
        $this->assertTrue($group->canInviteMembers($owner));
    }

    public function test_admin_has_correct_permissions()
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'max_members' => 50,
        ]);

        $group->members()->attach($admin->user_handle, ['role' => 'admin']);

        $this->assertTrue($group->isAdmin($admin));
        $this->assertTrue($group->isModerator($admin));
        $this->assertTrue($group->canManageMembers($admin));
        $this->assertTrue($group->canManageContent($admin));
        $this->assertTrue($group->canInviteMembers($admin));
    }

    public function test_moderator_has_correct_permissions()
    {
        $owner = User::factory()->create();
        $moderator = User::factory()->create();
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'max_members' => 50,
        ]);

        $group->members()->attach($moderator->user_handle, ['role' => 'moderator']);

        $this->assertFalse($group->isAdmin($moderator));
        $this->assertTrue($group->isModerator($moderator));
        $this->assertFalse($group->canManageMembers($moderator));
        $this->assertTrue($group->canManageContent($moderator));
        $this->assertTrue($group->canInviteMembers($moderator));
    }

    public function test_member_has_limited_permissions()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'max_members' => 50,
        ]);

        $group->members()->attach($member->user_handle, ['role' => 'member']);

        $this->assertFalse($group->isAdmin($member));
        $this->assertFalse($group->isModerator($member));
        $this->assertFalse($group->canManageMembers($member));
        $this->assertFalse($group->canManageContent($member));
        $this->assertFalse($group->canInviteMembers($member));
        $this->assertTrue($group->isMember($member));
    }

    public function test_role_permissions_are_correct()
    {
        $owner = Group::ROLE_PERMISSIONS['owner'];
        $admin = Group::ROLE_PERMISSIONS['admin'];
        $moderator = Group::ROLE_PERMISSIONS['moderator'];
        $member = Group::ROLE_PERMISSIONS['member'];

        // Owner should have all permissions
        $this->assertContains('manage_group', $owner);
        $this->assertContains('manage_members', $owner);
        $this->assertContains('manage_content', $owner);
        $this->assertContains('invite_members', $owner);
        $this->assertContains('chat', $owner);

        // Admin should have most permissions except manage_group
        $this->assertNotContains('manage_group', $admin);
        $this->assertContains('manage_members', $admin);
        $this->assertContains('manage_content', $admin);
        $this->assertContains('invite_members', $admin);
        $this->assertContains('chat', $admin);

        // Moderator should have limited permissions
        $this->assertNotContains('manage_group', $moderator);
        $this->assertNotContains('manage_members', $moderator);
        $this->assertContains('manage_content', $moderator);
        $this->assertContains('chat', $moderator);

        // Member should only have chat permission
        $this->assertCount(1, $member);
        $this->assertContains('chat', $member);
    }
} 
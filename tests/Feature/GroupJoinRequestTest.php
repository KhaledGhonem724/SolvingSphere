<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupJoinRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupJoinRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_to_join_private_group()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'visibility' => 'private',
            'join_policy' => 'apply_approve'
        ]);

        $response = $this->actingAs($user)->post("/groups/{$group->id}/join-requests", [
            'message' => 'I would like to join this group'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('group_join_requests', [
            'group_id' => $group->id,
            'user_id' => $user->user_handle,
            'status' => 'pending',
            'message' => 'I would like to join this group'
        ]);
    }

    public function test_admin_can_approve_join_request()
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        $applicant = User::factory()->create();
        
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'visibility' => 'private',
            'join_policy' => 'apply_approve'
        ]);

        $group->members()->attach($admin->user_handle, ['role' => 'admin']);

        $request = GroupJoinRequest::create([
            'group_id' => $group->id,
            'user_id' => $applicant->user_handle,
            'message' => 'I would like to join',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->post("/groups/{$group->id}/join-requests/{$request->id}/approve");

        $response->assertRedirect();
        $this->assertDatabaseHas('group_join_requests', [
            'id' => $request->id,
            'status' => 'approved'
        ]);
        $this->assertDatabaseHas('group_members', [
            'group_id' => $group->id,
            'user_id' => $applicant->user_handle,
            'role' => 'member'
        ]);
    }

    public function test_admin_can_reject_join_request()
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create();
        $applicant = User::factory()->create();
        
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'visibility' => 'private',
            'join_policy' => 'apply_approve'
        ]);

        $group->members()->attach($admin->user_handle, ['role' => 'admin']);

        $request = GroupJoinRequest::create([
            'group_id' => $group->id,
            'user_id' => $applicant->user_handle,
            'message' => 'I would like to join',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->post("/groups/{$group->id}/join-requests/{$request->id}/reject", [
            'reason' => 'Group is currently full'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('group_join_requests', [
            'id' => $request->id,
            'status' => 'rejected',
            'response' => 'Group is currently full'
        ]);
        $this->assertDatabaseMissing('group_members', [
            'group_id' => $group->id,
            'user_id' => $applicant->user_handle
        ]);
    }

    public function test_moderator_cannot_manage_join_requests()
    {
        $owner = User::factory()->create();
        $moderator = User::factory()->create();
        $applicant = User::factory()->create();
        
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'visibility' => 'private',
            'join_policy' => 'apply_approve'
        ]);

        $group->members()->attach($moderator->user_handle, ['role' => 'moderator']);

        $request = GroupJoinRequest::create([
            'group_id' => $group->id,
            'user_id' => $applicant->user_handle,
            'message' => 'I would like to join',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($moderator)->post("/groups/{$group->id}/join-requests/{$request->id}/approve");
        $response->assertStatus(403);

        $response = $this->actingAs($moderator)->post("/groups/{$group->id}/join-requests/{$request->id}/reject");
        $response->assertStatus(403);
    }

    public function test_cannot_request_to_join_public_group()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'visibility' => 'public',
            'join_policy' => 'free'
        ]);

        $response = $this->actingAs($user)->post("/groups/{$group->id}/join-requests", [
            'message' => 'I would like to join'
        ]);

        $response->assertStatus(422);
    }
} 
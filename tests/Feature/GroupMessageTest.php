<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_member_can_send_message()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create(['owner_id' => $owner->user_handle]);
        
        $group->members()->attach($member->user_handle, ['role' => 'member']);

        $response = $this->actingAs($member)->post("/groups/{$group->id}/messages", [
            'content' => 'Test message'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('group_messages', [
            'group_id' => $group->id,
            'user_id' => $member->user_handle,
            'content' => 'Test message'
        ]);
    }

    public function test_non_member_cannot_send_message()
    {
        $owner = User::factory()->create();
        $nonMember = User::factory()->create();
        $group = Group::factory()->create(['owner_id' => $owner->user_handle]);

        $response = $this->actingAs($nonMember)->post("/groups/{$group->id}/messages", [
            'content' => 'Test message'
        ]);

        $response->assertStatus(403);
    }

    public function test_message_read_status()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create(['owner_id' => $owner->user_handle]);
        
        $group->members()->attach($member->user_handle, ['role' => 'member']);

        $message = GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => $owner->user_handle,
            'content' => 'Test message',
            'read_by' => []
        ]);

        $response = $this->actingAs($member)->post("/groups/{$group->id}/messages/{$message->id}/read");
        
        $response->assertRedirect();
        $this->assertDatabaseHas('group_messages', [
            'id' => $message->id,
            'read_by' => json_encode([$member->user_handle])
        ]);
    }

    public function test_moderator_can_delete_message()
    {
        $owner = User::factory()->create();
        $moderator = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create(['owner_id' => $owner->user_handle]);
        
        $group->members()->attach($moderator->user_handle, ['role' => 'moderator']);
        $group->members()->attach($member->user_handle, ['role' => 'member']);

        $message = GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => $member->user_handle,
            'content' => 'Test message'
        ]);

        $response = $this->actingAs($moderator)->delete("/groups/{$group->id}/messages/{$message->id}");
        
        $response->assertRedirect();
        $this->assertSoftDeleted('group_messages', [
            'id' => $message->id
        ]);
    }

    public function test_message_validation()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create(['owner_id' => $owner->user_handle]);
        
        $group->members()->attach($member->user_handle, ['role' => 'member']);

        $response = $this->actingAs($member)->post("/groups/{$group->id}/messages", [
            'content' => ''
        ]);

        $response->assertStatus(422);
    }
} 
namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_group()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/groups', [
            'name' => 'Test Group',
            'short_name' => 'test-group',
            'description' => 'Test group description',
            'visibility' => 'public',
            'join_policy' => 'free',
            'max_members' => 50
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('groups', [
            'name' => 'Test Group',
            'short_name' => 'test-group'
        ]);
    }

    public function test_group_owner_can_update_settings()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create(['owner_id' => $user->user_handle]);

        $response = $this->actingAs($user)->put("/groups/{$group->id}", [
            'name' => 'Updated Group',
            'visibility' => 'private',
            'join_policy' => 'invite_only'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Updated Group',
            'visibility' => 'private',
            'join_policy' => 'invite_only'
        ]);
    }

    public function test_user_can_join_public_group()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'visibility' => 'public',
            'join_policy' => 'free'
        ]);

        $response = $this->actingAs($user)->post("/groups/{$group->id}/join");

        $response->assertRedirect();
        $this->assertDatabaseHas('group_members', [
            'group_id' => $group->id,
            'user_id' => $user->user_handle,
            'role' => 'member'
        ]);
    }

    public function test_user_can_apply_to_private_group()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'visibility' => 'private',
            'join_policy' => 'apply_approve'
        ]);

        $response = $this->actingAs($user)->post("/groups/{$group->id}/join-requests", [
            'message' => 'Please let me join'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('group_join_requests', [
            'group_id' => $group->id,
            'user_id' => $user->user_handle,
            'status' => 'pending'
        ]);
    }

    public function test_owner_can_manage_members()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $group = Group::factory()->create(['owner_id' => $owner->user_handle]);
        
        // Add member
        $group->members()->attach($member->user_handle, ['role' => 'member']);

        // Change role
        $response = $this->actingAs($owner)->put("/groups/{$group->id}/members/{$member->user_handle}", [
            'role' => 'moderator'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('group_members', [
            'group_id' => $group->id,
            'user_id' => $member->user_handle,
            'role' => 'moderator'
        ]);

        // Remove member
        $response = $this->actingAs($owner)->delete("/groups/{$group->id}/members/{$member->user_handle}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('group_members', [
            'group_id' => $group->id,
            'user_id' => $member->user_handle
        ]);
    }

    public function test_group_member_limits()
    {
        $owner = User::factory()->create();
        $group = Group::factory()->create([
            'owner_id' => $owner->user_handle,
            'max_members' => 2
        ]);

        // Add first member (should succeed)
        $member1 = User::factory()->create();
        $response1 = $this->actingAs($member1)->post("/groups/{$group->id}/join");
        $response1->assertRedirect();

        // Add second member (should succeed)
        $member2 = User::factory()->create();
        $response2 = $this->actingAs($member2)->post("/groups/{$group->id}/join");
        $response2->assertRedirect();

        // Try to add third member (should fail)
        $member3 = User::factory()->create();
        $response3 = $this->actingAs($member3)->post("/groups/{$group->id}/join");
        $response3->assertStatus(422);
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50|unique:groups',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private',
            'join_policy' => 'required|in:free,apply_approve,invite_only',
            'max_members' => 'required|integer|min:2'
        ]);

        $group = Group::create([
            ...$validated,
            'owner_id' => Auth::user()->user_handle
        ]);

        // إضافة المالك كعضو في المجموعة
        $group->members()->attach(Auth::user()->user_handle, ['role' => 'owner']);

        return redirect()->route('groups.show', $group);
    }

    public function update(Request $request, Group $group)
    {
        if (!$group->isAdmin(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'sometimes|required|in:public,private',
            'join_policy' => 'sometimes|required|in:free,apply_approve,invite_only',
            'max_members' => 'sometimes|required|integer|min:2'
        ]);

        $group->update($validated);

        return redirect()->route('groups.show', $group);
    }

    public function join(Group $group)
    {
        if (!$group->canJoin(Auth::user())) {
            throw ValidationException::withMessages([
                'group' => ['لا يمكنك الانضمام إلى هذه المجموعة.']
            ]);
        }

        $group->members()->attach(Auth::user()->user_handle, ['role' => 'member']);

        return redirect()->route('groups.show', $group);
    }

    public function updateMember(Request $request, Group $group, User $user)
    {
        if (!$group->canManageMembers(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,moderator,member'
        ]);

        if ($user->user_handle === $group->owner_id) {
            abort(403, 'لا يمكن تغيير دور مالك المجموعة');
        }

        $group->members()->updateExistingPivot($user->user_handle, [
            'role' => $validated['role']
        ]);

        return redirect()->route('groups.members', $group);
    }

    public function removeMember(Group $group, User $user)
    {
        if (!$group->canManageMembers(Auth::user())) {
            abort(403);
        }

        if ($user->user_handle === $group->owner_id) {
            abort(403, 'لا يمكن إزالة مالك المجموعة');
        }

        $group->members()->detach($user->user_handle);

        return redirect()->route('groups.members', $group);
    }
} 
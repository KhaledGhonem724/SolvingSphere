<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupJoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GroupJoinRequestController extends Controller
{
    public function store(Request $request, Group $group)
    {
        if ($group->visibility === 'public' && $group->join_policy === 'free') {
            throw ValidationException::withMessages([
                'group' => ['هذه المجموعة عامة ويمكنك الانضمام إليها مباشرة.']
            ]);
        }

        if ($group->isMember(Auth::user())) {
            throw ValidationException::withMessages([
                'group' => ['أنت عضو بالفعل في هذه المجموعة.']
            ]);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $joinRequest = $group->joinRequests()->create([
            'user_id' => Auth::user()->user_handle,
            'message' => $validated['message'],
            'status' => GroupJoinRequest::STATUS_PENDING
        ]);

        return redirect()->route('groups.show', $group);
    }

    public function approve(Group $group, GroupJoinRequest $request)
    {
        if (!$group->canManageMembers(Auth::user())) {
            abort(403);
        }

        if (!$request->isPending()) {
            throw ValidationException::withMessages([
                'request' => ['تم معالجة هذا الطلب بالفعل.']
            ]);
        }

        $request->approve();

        return redirect()->route('groups.join-requests', $group);
    }

    public function reject(Request $httpRequest, Group $group, GroupJoinRequest $request)
    {
        if (!$group->canManageMembers(Auth::user())) {
            abort(403);
        }

        if (!$request->isPending()) {
            throw ValidationException::withMessages([
                'request' => ['تم معالجة هذا الطلب بالفعل.']
            ]);
        }

        $validated = $httpRequest->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $request->reject($validated['reason']);

        return redirect()->route('groups.join-requests', $group);
    }
} 
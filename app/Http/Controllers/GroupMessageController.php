<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMessageController extends Controller
{
    public function store(Request $request, Group $group)
    {
        if (!$group->isMember(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000'
        ]);

        $message = $group->messages()->create([
            'content' => $validated['content'],
            'user_id' => Auth::user()->user_handle,
            'read_by' => [Auth::user()->user_handle]
        ]);

        return redirect()->route('groups.messages', $group);
    }

    public function markAsRead(Group $group, GroupMessage $message)
    {
        if (!$group->isMember(Auth::user())) {
            abort(403);
        }

        $message->markAsRead(Auth::user());

        return redirect()->route('groups.messages', $group);
    }

    public function destroy(Group $group, GroupMessage $message)
    {
        if (!$group->canManageContent(Auth::user()) && $message->user_id !== Auth::user()->user_handle) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('groups.messages', $group);
    }
} 
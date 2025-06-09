<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupChatController extends Controller
{
    public function index(Group $group)
    {
        if (!$group->isMember(Auth::user())) {
            abort(403, 'غير مصرح لك بعرض الرسائل');
        }

        $messages = $group->messages()
            ->with(['user', 'readBy'])
            ->latest()
            ->paginate(50);

        return inertia('Groups/Chat/Index', [
            'group' => $group,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request, Group $group)
    {
        if (!$group->isMember(Auth::user())) {
            abort(403, 'غير مصرح لك بإرسال رسائل');
        }

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = $group->messages()->create([
            'message' => $request->message,
            'user_id' => Auth::user()->user_handle,
        ]);

        return redirect()->back()->with('success', 'تم إرسال الرسالة بنجاح');
    }

    public function destroy(Group $group, GroupMessage $message)
    {
        if (!$group->canManageContent(Auth::user()) && $message->user_id !== Auth::user()->user_handle) {
            abort(403, 'غير مصرح لك بحذف هذه الرسالة');
        }

        $message->delete();

        return redirect()->back()->with('success', 'تم حذف الرسالة بنجاح');
    }

    public function markAsRead(Group $group, GroupMessage $message)
    {
        if (!$group->isMember(Auth::user())) {
            abort(403, 'غير مصرح لك بتحديث حالة القراءة');
        }

        $message->readBy()->attach(Auth::user()->user_handle);

        return redirect()->back();
    }
} 
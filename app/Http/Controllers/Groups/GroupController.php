<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Groups\CreateGroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['owner', 'members'])
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when(request('filter') === 'my_groups', function ($query) {
                $query->whereHas('members', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Groups/Index', [
            'groups' => $groups,
            'filters' => request()->only(['search', 'filter']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Groups/Create', [
            'joinPolicies' => Group::JOIN_POLICIES,
            'visibilityOptions' => Group::VISIBILITY_OPTIONS
        ]);
    }

    public function store(CreateGroupRequest $request)
    {
        $validated = $request->validated();
        
        $group = Group::create([
            'name' => $validated['name'],
            'short_name' => $validated['short_name'],
            'description' => $validated['brief'],
            'visibility' => $validated['visibility'],
            'join_policy' => $validated['join_policy'],
            'max_members' => 100, // قيمة افتراضية
            'owner_id' => Auth::id(),
        ]);

        // إضافة المنشئ كعضو owner
        $group->members()->attach(Auth::id(), [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('groups.show', $group)
            ->with('success', 'تم إنشاء المجموعة بنجاح!');
    }

    public function show(Group $group)
    {
        $group->load([
            'owner',
            'members',
            'materials' => fn($q) => $q->latest()->take(5),
            'topics' => fn($q) => $q->latest()->take(5),
            'messages' => fn($q) => $q->latest()->take(5),
        ]);

        return Inertia::render('Groups/Show', [
            'group' => $group,
            'userRole' => $group->getMemberRole(Auth::user()),
            'canJoin' => !$group->isMember(Auth::user()),
            'roles' => Group::ROLES,
        ]);
    }

    public function edit(Group $group)
    {
        $this->authorize('update', $group);

        return Inertia::render('Groups/Edit', [
            'group' => $group,
            'joinPolicies' => Group::JOIN_POLICIES,
            'visibilityOptions' => Group::VISIBILITY_OPTIONS
        ]);
    }

    public function update(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'visibility' => 'required|in:public,private',
            'join_policy' => 'required|in:free,invite_only,apply_approve',
        ]);

        $group->update($validated);

        return redirect()->route('groups.show', $group)
            ->with('success', 'تم تحديث المجموعة بنجاح!');
    }

    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'تم حذف المجموعة بنجاح!');
    }

    public function join(Group $group)
    {
        if ($group->isMember(Auth::user())) {
            return back()->with('error', 'أنت عضو بالفعل في هذه المجموعة!');
        }

        if ($group->join_policy === 'free') {
            $group->members()->attach(Auth::id(), [
                'role' => 'member',
                'joined_at' => now(),
            ]);
            return back()->with('success', 'تم الانضمام للمجموعة بنجاح!');
        }

        // إنشاء طلب انضمام للمجموعات التي تتطلب موافقة
        if ($group->join_policy === 'apply_approve') {
            $group->joinRequests()->create([
                'user_id' => Auth::id(),
                'message' => request('message'),
            ]);
            return back()->with('success', 'تم إرسال طلب الانضمام بنجاح!');
        }

        return back()->with('error', 'هذه المجموعة تتطلب دعوة للانضمام!');
    }

    public function leave(Group $group)
    {
        if ($group->owner_id === Auth::id()) {
            return back()->with('error', 'لا يمكن لمالك المجموعة مغادرتها!');
        }

        $group->members()->detach(Auth::id());

        return redirect()->route('groups.index')
            ->with('success', 'تم مغادرة المجموعة بنجاح!');
    }
}

<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GroupMaterialController extends Controller
{
    public function index(Group $group)
    {
        $this->authorize('viewAny', [GroupMaterial::class, $group]);

        $materials = $group->materials()
            ->with('author')
            ->when(request('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            ->when(request('category'), function ($query, $category) {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Groups/Materials/Index', [
            'group' => $group,
            'materials' => $materials,
            'filters' => request()->only(['search', 'category']),
        ]);
    }

    public function create(Group $group)
    {
        $this->authorize('create', [GroupMaterial::class, $group]);

        return Inertia::render('Groups/Materials/Create', [
            'group' => $group,
        ]);
    }

    public function store(Request $request, Group $group)
    {
        $this->authorize('create', [GroupMaterial::class, $group]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:50',
        ]);

        $material = $group->materials()->create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('groups.materials.show', [$group, $material])
            ->with('success', 'تم إضافة المادة التعليمية بنجاح!');
    }

    public function show(Group $group, GroupMaterial $material)
    {
        $this->authorize('view', [$material, $group]);

        $material->load('author');

        return Inertia::render('Groups/Materials/Show', [
            'group' => $group,
            'material' => $material,
        ]);
    }

    public function edit(Group $group, GroupMaterial $material)
    {
        $this->authorize('update', [$material, $group]);

        return Inertia::render('Groups/Materials/Edit', [
            'group' => $group,
            'material' => $material,
        ]);
    }

    public function update(Request $request, Group $group, GroupMaterial $material)
    {
        $this->authorize('update', [$material, $group]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:50',
        ]);

        $material->update($validated);

        return redirect()->route('groups.materials.show', [$group, $material])
            ->with('success', 'تم تحديث المادة التعليمية بنجاح!');
    }

    public function destroy(Group $group, GroupMaterial $material)
    {
        $this->authorize('delete', [$material, $group]);

        $material->delete();

        return redirect()->route('groups.materials.index', $group)
            ->with('success', 'تم حذف المادة التعليمية بنجاح!');
    }
} 
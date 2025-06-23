<?php
namespace App\Http\Controllers\Admins;
use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\Authority;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('authorities')->get();
        return view('admins/staff/roles/index', compact('roles'));
    }

    public function edit(Role $role)
    {
        $authorities = Authority::all();
        return view('admins/staff/roles/edit', compact('role', 'authorities'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'authorities' => 'array',
            'authorities.*' => 'exists:authorities,id',
        ]);

        $role->update($request->only(['name', 'description']));
        $role->authorities()->sync($request->authorities ?? []);

        return redirect()->route('staff.roles.index')->with('success', 'Role updated successfully.');
    }

    public function create()
    {
        $authorities = Authority::all();
        return view('admins.staff.roles.create', compact('authorities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'authorities' => 'array',
            'authorities.*' => 'exists:authorities,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $role->authorities()->attach($validated['authorities'] ?? []);

        return redirect()->route('staff.roles.index')->with('success', 'Role created successfully.');
    }
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('staff.roles.index')->with('success', 'Role deleted successfully.');
    }
}

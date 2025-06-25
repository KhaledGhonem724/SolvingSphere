<?php
namespace App\Http\Controllers\Admins;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::with('role')
            ->whereHas('role', fn($q) => $q->where('name', '!=', 'user'))
            ->get();

        return view('admins.staff.admins.index', compact('admins'));
    }

    public function create()
    {
        $adminRoles = Role::where('name', '!=', 'user')->get(); // Exclude regular users
        return view('admins.staff.admins.create', compact('adminRoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_handle' => 'required|string|unique:users,user_handle',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'user_handle' => $validated['user_handle'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // auto-hashed by model
            'role_id' => $validated['role_id'],
            'status' => 'active',
        ]);

        return redirect()->route('staff.admins.index')->with('success', 'Admin created successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete(); // or just set role_id to null if you don't want to delete
        return redirect()->route('staff.admins.index')->with('success', 'Admin removed.');
    }

    public function promoteList(Request $request)
    {
        $query = User::with('role')
            ->where(function ($q) {
                $q->whereNull('role_id')
                ->orWhereHas('role', fn($r) => $r->where('name', 'user'));
            });

        if ($request->filled('user_handle')) {
            $query->where('user_handle', 'like', '%' . $request->user_handle . '%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $users = $query->paginate(10)->withQueryString(); // ← this preserves search filters

        $adminRoles = Role::where('name', '!=', 'user')->get();

        return view('admins.staff.admins.promote', compact('users', 'adminRoles'));
    }



    public function promote(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'role_id' => $request->role_id,
            'status' => 'admin', //  promote user to admin status
        ]);

        return redirect()
            ->route('staff.admins.index')
            ->with('success', "User '{$user->user_handle}' promoted successfully.");
    }

}

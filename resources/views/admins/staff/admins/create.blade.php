<x-temp-general-layout>
    <h1 class="text-2xl font-bold mb-6">Add New Admin</h1>

    <form method="POST" action="{{ route('staff.admins.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block font-semibold">User Handle</label>
            <input type="text" name="user_handle" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block font-semibold">Name</label>
            <input type="text" name="name" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block font-semibold">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block font-semibold">Role</label>
            <select name="role_id" class="w-full border p-2 rounded" required>
                @foreach($adminRoles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Add Admin
        </button>
    </form>
</x-temp-general-layout>

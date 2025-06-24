<x-temp-general-layout>
    <h1 class="text-2xl font-bold mb-6">Promote Existing Users to Admin</h1>
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-semibold">User Handle</label>
            <input type="text" name="user_handle" value="{{ request('user_handle') }}"
                class="border px-3 py-2 rounded w-48" placeholder="e.g. u20241234">
        </div>
        <div>
            <label class="block text-sm font-semibold">Name</label>
            <input type="text" name="name" value="{{ request('name') }}"
                class="border px-3 py-2 rounded w-48" placeholder="e.g. Ahmed">
        </div>
        <div class="flex gap-2 mt-5">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                🔍 Search
            </button>

            <a href="{{ route('staff.admins.promote') }}"
            class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                🔄 Reset
            </a>
        </div>
    </form>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($users->isEmpty())
        <p class="text-gray-500">No users available for promotion.</p>
    @else
        <table class="table-auto w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Handle</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Assign Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $user->user_handle }}</td>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">
                            <form action="{{ route('staff.admins.promote_user', $user) }}" method="POST" class="flex space-x-2">
                                @csrf
                                <select name="role_id" class="border rounded px-2 py-1" required>
                                    <option value="">Select Role</option>
                                    @foreach ($adminRoles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                    Promote
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6 flex justify-center">
            {{ $users->links() }}
        </div>
    @endif
</x-temp-general-layout>

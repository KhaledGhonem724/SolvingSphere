<x-temp-general-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Admins Management</h1>
        <div class="space-x-2">
            <a href="{{ route('staff.admins.create') }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                + Add Admin
            </a>
            <a href="{{ route('staff.admins.promote') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Promote Existing User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="table-auto w-full border border-gray-300">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">Handle</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($admins as $admin)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $admin->user_handle }}</td>
                    <td class="px-4 py-2">{{ $admin->name }}</td>
                    <td class="px-4 py-2">{{ $admin->email }}</td>
                    <td class="px-4 py-2">{{ $admin->role->name ?? '-' }}</td>
                    <td class="px-4 py-2">
                        <form action="{{ route('staff.admins.destroy', $admin) }}" method="POST"
                              onsubmit="return confirm('Remove this admin?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-4 text-center text-gray-500">No admins found.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-temp-general-layout>

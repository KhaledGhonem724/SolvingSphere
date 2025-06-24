<x-temp-general-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Roles Management</h1>
        <a href="{{ route('staff.roles.create') }}"
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Create New Role
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="table-auto w-full border border-gray-300">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Authorities</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $role->id }}</td>
                    <td class="px-4 py-2">{{ $role->name }}</td>
                    <td class="px-4 py-2">{{ $role->description }}</td>
                    <td class="px-4 py-2">
                        @forelse($role->authorities as $authority)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                {{ $authority->name }}
                            </span>
                        @empty
                            <span class="text-gray-500 text-sm">No authorities</span>
                        @endforelse
                    </td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('staff.roles.edit', $role) }}"
                           class="text-blue-600 hover:underline">Edit</a>

                        <form action="{{ route('staff.roles.destroy', $role) }}" method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Are you sure you want to delete this role?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-temp-general-layout>

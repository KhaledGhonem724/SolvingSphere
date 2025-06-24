<x-temp-general-layout>
    <h1 class="text-2xl font-bold mb-6">Edit Role: {{ $role->name }}</h1>

    <form action="{{ route('staff.roles.update', $role) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-semibold">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label for="description" class="block font-semibold">Description</label>
            <textarea name="description" id="description" class="w-full border p-2 rounded">{{ old('description', $role->description) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-2">Authorities</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($authorities as $authority)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="authorities[]" value="{{ $authority->id }}"
                            @checked(in_array($authority->id, $role->authorities->pluck('id')->toArray()))>
                        <span>{{ $authority->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Role</button>
    </form>
</x-temp-general-layout>

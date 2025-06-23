<x-temp-general-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Create General Task</h2>
    </x-slot>

    <form method="POST" action="{{ route('staff.tasks.store') }}">
        @csrf

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Optional Title --}}
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Task Title</label>
            <input type="text" name="title" id="title"
                   value="{{ old('title') }}"
                   class="form-input mt-1 w-full border-gray-300 rounded-md shadow-sm"
                   placeholder="Optional title">
        </div>

        {{-- Task Creator (Admin who is creating it) --}}
        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700">Task Creator</label>
            <input type="text" name="user_id" id="user_id"
                   value="{{ old('user_id', auth()->user()->user_handle) }}"
                   class="form-input mt-1 w-full border-gray-300 rounded-md shadow-sm">
        </div>

        {{-- Type --}}
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Task Type</label>
            <select name="type" id="type" class="form-select mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Type</option>
                @foreach ($types as $type)
                    <option value="{{ $type->value }}" @selected(old('type') == $type->value)>
                        {{ $type->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Authority --}}
        <div class="mb-4">
            <label for="authority_id" class="block text-sm font-medium text-gray-700">Assign to Authority</label>
            <select name="authority_id" id="authority_id"
                    class="form-select mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Select Authority</option>
                @foreach ($authorities as $authority)
                    <option value="{{ $authority->id }}" @selected(old('authority_id') == $authority->id)>
                        {{ $authority->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Assignee (optional) --}}
        <div class="mb-4">
            <label for="assignee_id" class="block text-sm font-medium text-gray-700">Assign to User</label>
            <select name="assignee_id" id="assignee_id"
                    class="form-select mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="">— No Assignee —</option>
                @foreach ($users as $user)
                    <option value="{{ $user->user_handle }}" @selected(old('assignee_id') == $user->user_handle)>
                        {{ $user->name }} ({{ $user->user_handle }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Manager Note (optional) --}}
        <div class="mb-4">
            <label for="manager_note" class="block text-sm font-medium text-gray-700">Manager Note</label>
            <textarea name="manager_note" id="manager_note" rows="4"
                      class="form-textarea mt-1 w-full border-gray-300 rounded-md shadow-sm"
                      placeholder="Optional manager note">{{ old('manager_note') }}</textarea>
        </div>

        {{-- Submit --}}
        <div>
            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Create Task
            </button>
        </div>
    </form>
</x-temp-general-layout>

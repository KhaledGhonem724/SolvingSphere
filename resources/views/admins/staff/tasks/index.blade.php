<x-temp-general-layout>
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">All Tasks</h2>
        <a href="{{ route('staff.tasks.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            + Create Task
        </a>
    </div>







    <div class="bg-white p-6 rounded-lg shadow border mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Status --}}
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" id="status" class="form-select w-full rounded-lg border-gray-300 shadow-sm">
                <option value="">All</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') == $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Type --}}
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type" id="type" class="form-select w-full rounded-lg border-gray-300 shadow-sm">
                <option value="">All</option>
                @foreach ($types as $type)
                    <option value="{{ $type->value }}" @selected(request('type') == $type->value)>
                        {{ $type->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Assignee ID --}}
        <div>
            <label for="assignee_id" class="block text-sm font-medium text-gray-700 mb-1">Assignee Handle</label>
            <input type="text" name="assignee_id" id="assignee_id" value="{{ request('assignee_id') }}"
                   class="form-input w-full rounded-lg border-gray-300 shadow-sm"
                   placeholder="e.g. coach_ahmed">
        </div>

        {{-- Authority --}}
        <div>
            <label for="authority_id" class="block text-sm font-medium text-gray-700 mb-1">Authority</label>
            <select name="authority_id" id="authority_id" class="form-select w-full rounded-lg border-gray-300 shadow-sm">
                <option value="">All</option>
                @foreach ($authorities as $authority)
                    <option value="{{ $authority->id }}" @selected(request('authority_id') == $authority->id)>
                        {{ $authority->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Buttons --}}
        <div class="md:col-span-4 flex justify-between mt-4">
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                Filter
            </button>

            <a href="{{ route('staff.tasks.index') }}"
               class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                Reset
            </a>
        </div>
    </form>
</div>











    <div class="mt-4 space-y-4">
        @foreach ($tasks as $task)
            <div class="p-4 rounded-lg border shadow-sm bg-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-lg">{{ $task->title ?? 'Untitled Task' }}</h3>
                        <p class="text-sm text-gray-500">Status: <strong>{{ $task->status->label() }}</strong></p>
                        <p class="text-sm">Type: {{ ucfirst($task->type) }}</p>
                        <p class="text-sm">Assignee: {{ $task->assignee->name ?? '—' }}</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('staff.tasks.show', $task->id) }}"
                           class="text-blue-500 hover:underline">View</a>

                        <form action="{{ route('staff.tasks.destroy', $task->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    </div>
</x-temp-general-layout>

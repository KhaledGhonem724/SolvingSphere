<x-temp-general-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tasks Assigned to: {{ $authority->name }}</h2>
    </x-slot>

    <div class="mt-4 space-y-4">
        @forelse ($tasks as $task)
            <div class="p-4 border rounded bg-white shadow-sm">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-lg">{{ $task->title ?? 'Untitled Task' }}</h3>
                        <p class="text-sm text-gray-500">Status: <strong>{{ $task->status->label() }}</strong></p>
                        <p class="text-sm">Type: {{ ucfirst($task->type) }}</p>
                        <p class="text-sm">Assignee: {{ $task->assignee->name ?? '—' }}</p>
                    </div>
                    <a href="{{ route('staff.tasks.show', $task->id) }}" class="text-blue-600 hover:underline">View</a>
                </div>
            </div>
        @empty
            <div class="p-4 text-gray-500">No tasks found for this authority.</div>
        @endforelse

        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    </div>
</x-temp-general-layout>

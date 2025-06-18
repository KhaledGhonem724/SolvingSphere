<x-temp-general-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">

        <!-- Task Header -->
        <h1 class="text-2xl font-bold mb-4">
            Task #{{ $task->id }} - {{ $task->title ?? 'Untitled' }}
        </h1>

        <!-- Access Check -->
        @php
            $user = auth()->user();
            $isManager = $user->hasAuthority('manage_tasks');
            $hasTargetAuthority = $user->hasAuthority($task->authority->name ?? '');
            $isAssignee = $user->user_handle === $task->assignee_id;
            $isUnassigned = !$task->assignee_id;
        @endphp

        @if (!$isManager && !$hasTargetAuthority)
            <!-- D. No Authority -->
            <div class="bg-white p-6 border rounded shadow text-center text-gray-700">
                <p class="text-lg font-semibold">You are not authorised to access this task.</p>
            </div>
        @else
            <!-- A. Manager -->
            @if ($isManager)
                <div class="mb-6">
                    <a href="{{ route('staff.tasks.edit', $task->id) }}"
                       class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                        ✏️ Edit Task
                    </a>
                </div>
            @endif

            <!-- B & C Buttons -->
            @if (!$isManager)
            <div class="mb-6 space-x-2">
                @if ($isAssignee)
                    <!-- Decline Task -->
                    <form method="POST" action="{{ route('staff.tasks.update_status', ['task_id' => $task->id, 'status' => \App\Enums\TaskStatus::Refused->value]) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-4 py-1 text-sm rounded text-white bg-red-600 hover:bg-red-700">
                            Decline Task
                        </button>
                    </form>

                    <!-- Mark as Solved -->
                    <form method="POST" action="{{ route('staff.tasks.update_status', ['task_id' => $task->id, 'status' => \App\Enums\TaskStatus::Solved->value]) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-4 py-1 text-sm rounded text-white bg-green-600 hover:bg-green-700">
                            Mark as Solved
                        </button>
                    </form>

                    <!-- Couldn't Complete -->
                    <form method="POST" action="{{ route('staff.tasks.update_status', ['task_id' => $task->id, 'status' => \App\Enums\TaskStatus::Dismissed->value]) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-4 py-1 text-sm rounded text-white bg-gray-600 hover:bg-gray-700">
                            Couldn't Complete
                        </button>
                    </form>
                
                @elseif ($hasTargetAuthority && $isUnassigned)
                    <!-- Accept Task -->
                    <form method="POST" action="{{ route('staff.tasks.update_status', ['task_id' => $task->id, 'status' => \App\Enums\TaskStatus::Assigned->value]) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-4 py-1 text-sm rounded text-white bg-blue-600 hover:bg-blue-700">
                            Accept Task
                        </button>
                    </form>
                @endif
            </div>
        @endif


            <!-- Metadata -->
            <div class="bg-white border rounded p-4 mb-6 text-sm space-y-1">
                <p><strong>Status:</strong> {{ ucfirst($task->status->value) }}</p>
                <p><strong>Assignee:</strong> {{ $task->assignee_id ?? '—' }}</p>
                <p><strong>Target Authority:</strong> {{ $task->authority->name ?? '—' }}</p>
                <p><strong>Reportable:</strong>
                    @if($task->reportable_type)
                        {{ class_basename($task->reportable_type) }} #{{ $task->reportable_id }}
                    @else
                        None
                    @endif
                </p>
                <p><strong>Created At:</strong> {{ $task->created_at->format('Y-m-d H:i') }}</p>
            </div>

            <!-- Origin Report Link -->
            @if($task->report_id && $isManager)
                <a href="{{ route('staff.reports.show', $task->report_id) }}"
                   class="text-blue-600 hover:underline text-sm font-medium mb-6 inline-block">
                    📄 View Origin Report
                </a>
            @endif

            <!-- Notes -->
            <div class="mb-6 space-y-4 text-sm">
                <div>
                    <h3 class="font-semibold">🧾 User Notes</h3>
                    <div class="bg-gray-50 p-3 rounded border">{{ $task->user_note ?? '—' }}</div>
                </div>
                <div>
                    <h3 class="font-semibold">📋 Manager Notes</h3>
                    <div class="bg-gray-50 p-3 rounded border">{{ $task->manager_note ?? '—' }}</div>
                </div>
                <div>
                    <h3 class="font-semibold">🗒 Admin Notes</h3>
                    <div class="bg-gray-50 p-3 rounded border">{{ $task->admin_note ?? '—' }}</div>
                </div>
            </div>

            <!-- Admin Note Form (Only manager or assignee) -->
            @if (!$isManager && $isAssignee)
                <div>
                    <form method="POST" action="{{ route('staff.tasks.add_admin_note', $task->id) }}">
                        @csrf
                        <label for="admin_note" class="block text-sm font-medium mb-1">Add Admin Note</label>
                        <textarea name="admin_note" id="admin_note" rows="4"
                                  class="form-textarea w-full border-gray-300 rounded shadow-sm mb-2"
                                  placeholder="Write your note here..."></textarea>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                            Submit Note
                        </button>
                    </form>
                </div>
            @endif
        @endif

    </div>
</x-temp-general-layout>

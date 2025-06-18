<x-temp-general-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">✏️ Edit Task #{{ $task->id }}</h1>

        <form method="POST" action="{{ route('staff.tasks.update', $task->id) }}">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium">Title</label>
                <input type="text" name="title" id="title" class="form-input w-full"
                       value="{{ old('title', $task->title) }}">
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium">Status</label>
                <select name="status" id="status" class="form-select w-full">
                    @foreach (\App\Enums\TaskStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $task->status->value) === $status->value)>
                            {{ ucfirst($status->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Assignee -->
            <div class="mb-4">
                <label for="assignee_id" class="block text-sm font-medium">Assignee</label>
                <select name="assignee_id" id="assignee_id" class="form-select w-full">
                    <option value="">— Unassigned —</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_handle }}" @selected(old('assignee_id', $task->assignee_id) === $user->user_handle)>
                            {{ $user->name }} ({{ $user->user_handle }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Authority -->
            <div class="mb-4">
                <label for="authority_id" class="block text-sm font-medium">Target Authority</label>
                <select name="authority_id" id="authority_id" class="form-select w-full">
                    <option value="">— None —</option>
                    @foreach ($authorities as $auth)
                        <option value="{{ $auth->id }}" @selected(old('authority_id', $task->authority_id) == $auth->id)>
                            {{ $auth->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label for="user_note" class="block text-sm font-medium">User Note</label>
                <textarea name="user_note" id="user_note" class="form-textarea w-full" rows="2">{{ old('user_note', $task->user_note) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="manager_note" class="block text-sm font-medium">Manager Note</label>
                <textarea name="manager_note" id="manager_note" class="form-textarea w-full" rows="2">{{ old('manager_note', $task->manager_note) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="admin_note" class="block text-sm font-medium">Admin Note</label>
                <textarea name="admin_note" id="admin_note" class="form-textarea w-full" rows="2">{{ old('admin_note', $task->admin_note) }}</textarea>
            </div>

            <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                💾 Save Changes
            </button>
        </form>
    </div>
</x-temp-general-layout>

<x-temp-general-layout>

    <div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Create Task from Report #{{ $report->id }}</h2>

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

            {{-- Hidden Inputs --}}
            <input type="hidden" name="report_id" value="{{ $report->id }}">
            <input type="hidden" name="user_id" value="{{ $report->user_id }}">
            <input type="hidden" name="type" value="{{ $report->type->value }}">
            <input type="hidden" name="user_note" value="{{ $report->message }}">

            {{-- Task Title --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Task Title</label>
                <input type="text" name="title" id="title"
                    value="{{ old('title') }}"
                    placeholder="Optional title"
                    class="form-input mt-1 w-full border-gray-300 rounded-md shadow-sm">
            </div>

            {{-- Authority --}}
            <div class="mb-4">
                <label for="authority_id" class="block text-sm font-medium text-gray-700">Assign to Authority</label>
                <select name="authority_id" id="authority_id"
                        class="form-select mt-1 w-full border-gray-300 rounded-md shadow-sm"
                >
                    <option value="">Select Authority</option>
                    @foreach ($authorities as $authority)
                        <option value="{{ $authority->id }}"
                                {{ old('authority_id') == $authority->id ? 'selected' : '' }}>
                            {{ $authority->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Assignee --}}
            <div class="mb-4">
                <label for="assignee_id" class="block text-sm font-medium text-gray-700">Assign to User (handle)</label>
                <input type="text" name="assignee_id" id="assignee_id"
                    value="{{ old('assignee_id') }}"
                    class="form-input mt-1 w-full border-gray-300 rounded-md shadow-sm"
                    placeholder="e.g. coach_john" >
            </div>

            {{-- Manager Note --}}
            <div class="mb-4">
                <label for="manager_note" class="block text-sm font-medium text-gray-700">Manager Note</label>
                <textarea name="manager_note" id="manager_note" rows="4"
                        class="form-textarea mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        placeholder="Write your manager note here...">{{ old('manager_note') }}</textarea>
            </div>

            {{-- Linked Report Info (Read-only) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Report Message</label>
                <div class="mt-1 p-3 border rounded bg-gray-50 text-sm text-gray-800 whitespace-pre-line">
                    {{ $report->message ?? 'No message provided.' }}
                </div>
            </div>

            {{-- Submit --}}
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                    Create Task
                </button>
        </form>

    </div>

</x-temp-general-layout>

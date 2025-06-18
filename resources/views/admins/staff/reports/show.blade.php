<x-temp-general-layout>

    <div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded">

        <!-- Header: ID + User + Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Report #{{ $report->id }}</h2>
                <p class="text-sm text-gray-600">Reported by <strong>{{ $report->reporter->user_handle ?? $report->user_id }}</strong></p>
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('staff.reports.create_task', ['report_id' => $report->id]) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
                    Create Task
                </a>
                <form method="POST" action="{{ route('staff.reports.show.mark_reviewed', $report->id) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
                        Mark as Reviewed
                    </button>
                </form>
                <form method="POST" action="{{ route('staff.reports.show.mark_ignored', $report->id) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition text-sm">
                        Mark as Ignored
                    </button>
                </form>
            </div>
        </div>

        <!-- Metadata -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700 mb-6">
            <div><strong>Type:</strong> {{ ucfirst($report->type->value) }}</div>
            <div><strong>Status:</strong> {{ ucfirst($report->status->value) }}</div>
            <div>
                <strong>Reportable:</strong>
                @if ($report->reportable_type)
                    {{ class_basename($report->reportable_type) }} (ID: {{ $report->reportable_id }})
                @else
                    <span class="italic text-gray-500">N/A</span>
                @endif
            </div>
            <div><strong>Date:</strong> {{ $report->created_at->format('Y-m-d H:i') }}</div>
        </div>

        <!-- User Message -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">User Message</h3>
            <div class="bg-gray-50 p-4 rounded border text-gray-800 text-sm whitespace-pre-line">
                {{ $report->message ?? 'No message provided.' }}
            </div>
        </div>

    </div>

</x-temp-general-layout>

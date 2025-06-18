<x-temp-general-layout title="Reports">
        <form method="GET" action="{{ route('staff.reports.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="form-select w-full">
                    <option value="">All</option>

                    @foreach($statuses  as $status)
                        <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                            {{ ucfirst($status->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" id="type" class="form-select w-full">
                    <option value="">All</option>
                    @foreach(  $types as $type)
                        <option value="{{ $type->value }}" @selected(request('type') === $type->value)>
                            {{ ucfirst($type->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Reporter Filter -->
            <div>
                <label for="user" class="block text-sm font-medium text-gray-700">Reporter Handle</label>
                <input type="text" name="user" id="user" value="{{ request('user') }}" class="form-input w-full">
            </div>

            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn btn-secondary ml-auto">Filter</button>
                <a href="{{ route('staff.reports.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>

        <!-- Reports List -->
        @forelse($reports as $report)
            <div class="p-4 mb-3 border rounded shadow-sm bg-white">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">

                    <!-- 1. Description -->
                    <div class="text-sm text-gray-600">
                        <strong>{{ ucfirst($report->type->value) }}</strong> report
                        by <span class="font-semibold">{{ $report->reporter->user_handle ?? $report->user_id }}</span>
                        @if(isset($report->reportable_type))
                            on <span class="italic">{{ class_basename($report->reportable_type) }}</span>
                            (ID: {{ $report->reportable_id }})
                        @endif
                    </div>

                    <!-- 2. Time & Status -->
                    <div class="text-center text-xs text-gray-500">
                        {{ $report->created_at->diffForHumans() }}<br>
                        <span class="inline-block mt-1 px-2 py-1 rounded bg-gray-100 text-gray-800">
                            {{ ucfirst($report->status->value) }}
                        </span>
                    </div>

                    <!-- 3. View Button -->
                    <div class="text-right">
                        <a href="{{ route('staff.reports.show', $report->id) }}"
                        class="inline-block px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                            View Report
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No reports found.</p>
        @endforelse




        <div class="mt-6">
            {{ $reports->links() }}
        </div>
</x-temp-general-layout>

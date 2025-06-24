<x-temp-general-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Submit a Report</h2>
    </x-slot>

    <form method="POST" action="{{ route('public.reports.store') }}">
        @csrf

        <input type="hidden" name="reportable_type" value="{{ old('reportable_type', $reportableType ?? request('type')) }}">
        <input type="hidden" name="reportable_id" value="{{ old('reportable_id', $reportableId ?? request('id')) }}">

        <div class="mt-4">
            <label for="type" class="block text-sm font-medium">Report Type</label>
            <select name="type" id="type" class="mt-1 block w-full border rounded">
                <option value="scientific">Scientific</option>
                <option value="ethical">Ethical</option>
                <option value="technical">Technical</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="mt-4">
            <label for="message" class="block text-sm font-medium">Message (optional)</label>
            <textarea name="message" id="message" rows="4" class="mt-1 block w-full border rounded"></textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Submit Report
            </button>
        </div>
    </form>
</x-temp-general-layout>

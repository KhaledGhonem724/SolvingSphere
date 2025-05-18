<x-temp-general-layout :title="$title ?? 'List Page'">
    <div class="mb-4">
        {{-- Filters Slot --}}
        {{ $filters ?? '' }}
    </div>

    <div class="mb-4">
        {{-- Title Slot --}}
        <h1 class="text-2xl font-bold">{{ $pageTitle ?? 'Page Title' }}</h1>
    </div>

    <div>
        {{-- Items Slot --}}
        {{ $items ?? '' }}
    </div>
</x-temp-general-layout>

@props([
    'title',
    'creation',
])

<div class="p-6 space-y-6">

    <!-- Header: Title and Create Button -->
    <div class="flex justify-between items-center mb-4">
        <div class="text-2xl font-bold">{{ $title }}</div>
        @isset($creation)
            <div class="flex items-center space-x-2">
                {{ $creation }}
            </div>
        @endisset
    </div>

    <!-- Filters -->
    @isset($filters)
        <div class="mb-4">
            {{ $filters }}
        </div>
    @endisset

    <!-- Items List -->
    <div class="space-y-4">
        {{ $items }}
    </div>

    <!-- Pagination -->
    @isset($pagination)
        <div class="mt-6">
            {{ $pagination }}
        </div>
    @endisset
</div>

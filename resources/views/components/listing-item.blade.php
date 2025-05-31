@props([
    'title',
    'href',
    'badge' => null,
    'badgeClass' => null,
    'meta' => null,
    'content' => null,
    'tags' => null,
])

<div class="p-4 border rounded-xl shadow-sm bg-white hover:shadow-md transition">
    <!-- Header: Title and Meta -->
    <div class="flex justify-between items-start">
        <div class="text-lg font-semibold">
            <a href="{{ $href }}">{{ $title }}</a>
        </div>
        @isset($badge)
            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">
                {{ $badge }}
            </span>
        @endisset
    </div>

    <!-- Sub Info (owner/time) -->
    @isset($meta)
        <div class="text-sm text-gray-500 mt-1">
            {{ $meta }}
        </div>
    @endisset

    <!-- Main Body -->
    @isset($content)
        <div class="mt-2 text-sm text-gray-700">
            {{ $content }}
        </div>
    @endisset

    <!-- Footer Tags -->
    @isset($tags)
        <div class="mt-3">
            {{ $tags }}
        </div>
    @endisset
</div>

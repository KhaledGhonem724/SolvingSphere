@props([
    'title',
    'sub_title' => null,
    'href',
    'badge' => null,
    'badgeClass' => null,
    'meta' => null,
    'content' => null,
    'tags' => null,
])

<div class="p-4 border rounded-xl shadow-sm bg-white hover:shadow-md transition">
    <!-- Header: Title and badge -->
    <div class="flex justify-between items-start">
        <div class="text-lg font-semibold">
            <a class="text-lg font-semibold pr-2" href="{{ $href }}">{{ $title }}</a>
            <span class="text-base font-normal">{{ $sub_title }}</span>
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

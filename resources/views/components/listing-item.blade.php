@props(['title', 'description', 'itemLink'])

<div
    class="p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition cursor-pointer"
    onclick="window.location='{{ $itemLink }}'"
    role="button"
    tabindex="0"
    onkeydown="if(event.key === 'Enter' || event.key === ' ') window.location='{{ $itemLink }}'"
>
    <h2 class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
    <p class="text-gray-600 mt-1">{{ $description }}</p>
    <p class="text-gray-600 mt-1">{{ $itemLink }}</p>
</div>


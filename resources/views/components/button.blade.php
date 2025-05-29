@props([
    'href' => null,
    'type' => 'submit',
    'color' => 'primary', // supports: primary, secondary, danger, etc.
])

@php
$baseClasses = 'inline-flex items-center px-4 py-2 rounded text-white font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-offset-2';
$colorClasses = match($color) {
    'primary' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
    'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
    default => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
};
$classes = "$baseClasses $colorClasses";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

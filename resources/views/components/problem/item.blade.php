@props([
    'problem',
    'title' => $problem->title,
    'badge' => ucfirst($problem->website),
    'badgeClass' => match($problem->website) {
        'attempted' => 'bg-blue-100 text-blue-700',
        'solved' => 'bg-green-100 text-green-700',
        default => 'bg-gray-100 text-gray-700',
    }
])
<x-listing-item :title="$title" :href="route('problems.show', $problem)" :badge="$badge" badgeClass="{{ $badgeClass }}">


    <x-slot:meta>
        Online Judge : <strong> {{ $problem->website }} </strong> • {{ $problem->created_at->diffForHumans() }}
    </x-slot:meta>

</x-listing-item>



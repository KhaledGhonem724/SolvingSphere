@props([
    'problem',
    'userSubmissions' => [],
    'title' => $problem->title,
    'sub_title' => $problem->problem_handle,
    'tags' => $problem->tags,
])
<x-listing-item 
    :title="$title" 
    :sub_title="$sub_title" 
    :href="route('problems.show', $problem)">

    @auth
        @isset($userSubmissions)
            @php
                $status = $userSubmissions[$problem->problem_handle]->result ?? 'todo';
                $badgeClass = match($status) {
                    'solved' => 'bg-green-100 text-green-800',
                    'attempted' => 'bg-yellow-100 text-yellow-800',
                    default => 'bg-gray-100 text-gray-700'
                };
            @endphp
            <x-slot:badge>
                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                    {{ ucfirst($status) }}
                </span>
            </x-slot:badge>
        @endisset
    @endauth

    <x-slot:meta>
        Online Judge : <strong> {{ $problem->website }} </strong> • {{ $problem->created_at->diffForHumans() }}
    </x-slot:meta>
    
    <x-slot:tags>
        <div class="flex flex-wrap gap-2">
            @foreach ($tags as $tag)
                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>
    </x-slot:tags>
</x-listing-item>



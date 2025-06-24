@props([
    'blog',
    'title' => $blog->title,
    'badge' => ucfirst($blog->blog_type),
    'tags' => $blog->tags,
    'badgeClass' => match($blog->blog_type) {
        'question' => 'bg-blue-100 text-blue-700',
        'discussion' => 'bg-yellow-100 text-yellow-700',
        'explain' => 'bg-green-100 text-green-700',
        default => 'bg-gray-100 text-gray-700',
    },
])
<x-listing-item 
    :title="$title" 
    :href="route('blogs.show', $blog)" 
    :badge="$badge" 
    badgeClass="{{ $badgeClass }}">


    <x-slot:meta>
        Posted by {{ $blog->owner->name }} • {{ $blog->created_at->diffForHumans() }}
    </x-slot:meta>

    <x-slot:content>
        {{ Str::limit(strip_tags($blog->content), 200) }}
    </x-slot:content>

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

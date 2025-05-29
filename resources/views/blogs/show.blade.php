<x-temp-general-layout :title="$blog->title">
    <h1 class="text-3xl font-bold mb-2">{{ $blog->title }}</h1>
    <p class="text-gray-600 mb-2">By {{ $blog->owner->name ?? $blog->owner_id }} • {{ $blog->created_at->diffForHumans() }}</p>
    <p class="mb-4 text-sm uppercase text-gray-400">{{ $blog->blog_type }}</p>

    <div class="prose max-w-none mb-6">
        {!! nl2br(e($blog->content)) !!}
    </div>

    <div class="flex gap-4">
        <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-sm btn-secondary">Edit</a>

        <form action="{{ route('blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Are you sure?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>
    </div>
</x-temp-general-layout>

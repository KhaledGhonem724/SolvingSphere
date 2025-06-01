<x-temp-general-layout :title="$blog->title">
    <!-- Card 1 - Blog  -->
    <article class="max-w-4xl mx-auto bg-white rounded-xl shadow p-8 space-y-6">
        <!-- Blog Title -->
        <h1 class="text-4xl font-bold text-gray-900 leading-tight">{{ $blog->title }}</h1>

        <!-- Author and Meta -->
        <div class="flex items-center text-sm text-gray-500 space-x-4">
            <span>
                By <span class="font-semibold text-gray-700">{{ $blog->owner->name ?? $blog->owner_id }}</span>
            </span>
            <span>•</span>
            <span>{{ $blog->created_at->diffForHumans() }}</span>
            <span>•</span>
            <span class="uppercase tracking-wide text-xs text-blue-500 font-medium">{{ $blog->blog_type }}</span>
        </div>

        <!-- Blog Tags -->
        @if ($blog->tags->isNotEmpty())
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach ($blog->tags as $tag)
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Blog Content -->
        <div class="prose max-w-none prose-lg prose-blue">
            <!-- new for TESTING -->
            {!! $blog->parsed_content !!}
        </div>


        <!-- Reactions -->
        @auth
            @php
                $userReaction = $blog->reactions->firstWhere('user_id', auth()->user()->user_handle)?->value;
            @endphp

            <div class="flex items-center gap-2 mt-4">
                <form action="{{ route('blogs.react', [$blog, 'value' => 1]) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-3 py-1 rounded-full text-sm font-semibold border transition 
                            {{ $userReaction === 1 ? 'bg-green-500 text-white border-green-500' : 'border-gray-300 text-gray-700 hover:bg-green-100' }}">
                        👍 Upvote
                    </button>
                </form>

                <form action="{{ route('blogs.react', [$blog, 'value' => -1]) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-3 py-1 rounded-full text-sm font-semibold border transition 
                            {{ $userReaction === -1 ? 'bg-red-500 text-white border-red-500' : 'border-gray-300 text-gray-700 hover:bg-red-100' }}">
                        👎 Downvote
                    </button>
                </form>

                <span class="ml-4 text-gray-600">Score: {{ $blog->score }}</span>
            </div>
        @endauth



        <!-- Action Buttons -->
        <div class="flex items-center gap-3 mt-4">
            @can('update', $blog)
                <x-button href="{{ route('blogs.edit', $blog) }}" color="secondary">
                    Edit
                </x-button>
            @endcan

            @can('delete', $blog)
                <form action="{{ route('blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <x-button color="danger" type="submit">
                        Delete
                    </x-button>
                </form>
            @endcan
        </div>

    </article>


    <!-- comment form + comments list -->
    <div class="mt-12 space-y-12 max-w-4xl mx-auto">
        <!-- Card 2 - comment form  -->
        <div class="bg-white p-6 rounded-xl shadow border">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Leave a Comment</h2>

            @auth
                <form action="{{ route('comments.store', $blog) }}" method="POST" class="space-y-4">
                    @csrf
                    <textarea
                        name="content"
                        rows="4"
                        required
                        placeholder="Write your comment..."
                        class="w-full p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >{{ old('content') }}</textarea>

                    @error('content')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 transition"
                    >
                        Submit
                    </button>
                </form>
            @else
                <p class="text-gray-600">
                    You must 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">log in</a>
                    to leave a comment.
                </p>
            @endauth
        </div>
        <!-- Card 3 - comments list  -->
        <div class="bg-white p-6 rounded-xl shadow border">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Comments</h2>

            @forelse ($blog->comments as $comment)
                <x-comment :comment="$comment" class="mb-6" />
            @empty
                <p class="text-gray-500 italic">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>
    </div>
    

    

</x-temp-general-layout>

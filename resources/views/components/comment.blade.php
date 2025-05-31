<div class="border border-gray-300 rounded-lg p-4 mb-4 {{ $comment->parent_id ? 'ml-8' : '' }} bg-white shadow-sm">
    <div class="flex items-center justify-between mb-2">
        <div>
            <span class="font-semibold text-gray-800">{{ $comment->commenter->name ?? $comment->commenter_id }}</span>
            <span class="text-gray-500 text-xs ml-2">{{ $comment->created_at->diffForHumans() }}</span>
        </div>

        <!-- edit/delete buttons here -->
        @can('update', $comment)
        <div class="flex gap-2 mt-2">
            <button onclick="document.getElementById('edit-form-{{ $comment->id }}').classList.toggle('hidden')"
                    class="text-sm text-blue-600 hover:underline">
                Edit
            </button>

            <form action="{{ route('comments.destroy', $comment) }}" method="POST" 
                onsubmit="return confirm('Are you sure you want to delete this comment?');"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
            </form>
        </div>

        <!-- Inline Edit Form -->
        <form action="{{ route('comments.update', $comment) }}" method="POST"
            id="edit-form-{{ $comment->id }}"
            class="mt-3 hidden"
        >
            @csrf
            @method('PUT')

            <textarea name="content" rows="3"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300"
                    required>{{ old('content', $comment->content) }}</textarea>

            <button type="submit" class="btn btn-sm btn-primary mt-2">Save Changes</button>
        </form>
        @endcan
    </div>

    <p class="text-gray-700 whitespace-pre-line">{{ $comment->content }}</p>
    
    <!-- reply button -->
    <button
        onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')"
        class="text-sm text-blue-600 hover:underline mt-3"
    >
        Reply
    </button>
    <!-- reply form -->
    <form
        action="{{ route('comments.store', $comment->blog) }}"
        method="POST"
        id="reply-form-{{ $comment->id }}"
        class="mt-3 hidden"
    >
        @csrf
        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
        <textarea
            name="content"
            required
            rows="3"
            placeholder="Write a reply..."
            class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300"
        ></textarea>
        <button type="submit" class="btn btn-sm btn-primary mt-2">Submit Reply</button>
    </form>

    @foreach($comment->replies as $reply)
        <x-comment :comment="$reply" />
    @endforeach
</div>
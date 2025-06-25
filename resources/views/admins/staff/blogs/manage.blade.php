<x-temp-general-layout>
    <h2 class="text-2xl font-bold">Manage Blogs</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Title</th>
                    <th class="py-2 px-4 border">Author</th>
                    <th class="py-2 px-4 border">Status</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $blog)
                    <tr class="{{ $blog->status === \App\Enums\VisibilityStatus::Hidden ? 'bg-red-50' : '' }}">
                        <td class="py-2 px-4 border">{{ $blog->id }}</td>
                        <td class="py-2 px-4 border">{{ $blog->title }}</td>
                        <td class="py-2 px-4 border">{{ $blog->owner->user_handle ?? 'Unknown' }}</td>
                        <td class="py-2 px-4 border">
                            <span class="inline-block px-2 py-1 rounded text-sm
                                {{ $blog->status === \App\Enums\VisibilityStatus::Visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $blog->status->label() }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border flex gap-2">
                            <a href="{{ route('blogs.show', $blog) }}" target="_blank"
                               class="text-blue-600 hover:underline">View</a>

                            <form method="POST" action="{{ route('staff.blogs.toggle_status', $blog) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="text-sm px-3 py-1 rounded 
                                    {{ $blog->status === \App\Enums\VisibilityStatus::Visible 
                                        ? 'bg-red-600 text-white hover:bg-red-700'
                                        : 'bg-green-600 text-white hover:bg-green-700' }}">
                                    {{ $blog->status === \App\Enums\VisibilityStatus::Visible ? 'Hide' : 'Unhide' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-temp-general-layout>

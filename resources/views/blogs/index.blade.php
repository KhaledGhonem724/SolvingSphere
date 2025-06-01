<x-temp-general-layout :title="$title ?? 'Blogs'">
    <!-- resources/views/blogs/index.blade.php -->
    <x-listing title="All Blogs">
        <x-slot:creation>
            <p>Express your thoughts!! </p>

            <x-button href="{{ route('blogs.create') }}" color="secondary">
                Create New Blog
            </x-button>
        </x-slot:creation>

        <x-slot:filters>
            <x-blog.filters :allTags="$allTags" />
        </x-slot:filters>

        <x-slot:items>
            @forelse ($blogs as $blog)
                <x-blog.item :blog="$blog"/>
            @empty
                <p>No blogs found.</p>
            @endforelse
        </x-slot:items>

        <x-slot:pagination>
            {{ $blogs->links() }}
        </x-slot:pagination>

    </x-listing>

</x-temp-general-layout>

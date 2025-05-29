<x-temp-general-layout :title="$title ?? 'All Blogs Page'">
    
<h1 class="text-2xl font-bold mb-4">All Blogs</h1>

    <a href="{{ route('blogs.create') }}" class="btn btn-primary mb-4">Create New Blog</a>
    <form method="GET" action="{{ route('blogs.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">

<div class="w-full md:w-1/3">
    <x-input 
        name="title" 
        label="Title" 
        :value="request('title')" 
        class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        label-class="block mb-1 font-semibold text-gray-700"
        input-class=""
    />
</div>

<div class="w-full md:w-1/4">
    <x-select 
        name="blog_type" 
        label="Type" 
        :options="[
            '' => 'All', 
            'discussion' => 'discussion', 
            'question' => 'question', 
            'explain' => 'explain'
        ]" 
        :value="request('blog_type')" 
        class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        label-class="block mb-1 font-semibold text-gray-700"
        select-class=""
    />
</div>

<div class="w-full md:w-1/4">
    <x-input 
        name="owner_id" 
        label="Owner ID" 
        :value="request('owner_id')" 
        class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        label-class="block mb-1 font-semibold text-gray-700"
        input-class=""
    />
</div>

<div class="w-full md:w-auto flex gap-2">
    <x-button>Filter</x-button>
    <x-button href="{{ route('blogs.index') }}" color="secondary">Reset</x-button>
</div>

</form>


    <a href="{{ route('blogs.index') }}" class="btn btn-secondary ml-2">Reset</a>

    @foreach ($blogs as $blog)
        <div class="border p-4 mb-3">
            <h2 class="text-xl font-semibold">
                <a href="{{ route('blogs.show', $blog) }}">{{ $blog->title }}</a>
            </h2>
            <p class="text-sm text-gray-500">By {{ $blog->owner->name ?? $blog->owner_id }} • {{ $blog->created_at->diffForHumans() }}</p>
            <p>{{ Str::limit($blog->content, 150) }}</p>
        </div>
    @endforeach

    {{ $blogs->links() }}

</x-temp-general-layout>

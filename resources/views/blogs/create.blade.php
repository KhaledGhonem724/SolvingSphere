<x-temp-general-layout title="Create Blog">
    <h1 class="text-2xl font-bold mb-4">Create New Blog</h1>

    <form action="{{ route('blogs.store') }}" method="POST">
        @csrf

        <x-input name="title" label="Title" />
        <x-textarea name="content" label="Content" />
        <x-select name="blog_type" label="Blog Type" :options="['discussion', 'question', 'explain']" />

        <button type="submit" class="btn btn-success mt-4">Submit</button>
    </form>
</x-temp-general-layout>

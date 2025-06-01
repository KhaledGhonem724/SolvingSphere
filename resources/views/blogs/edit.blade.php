<x-temp-general-layout :title="'Edit Blog: ' . $blog->title">
    <h1 class="text-2xl font-bold mb-4">Edit Blog</h1>

    <form action="{{ route('blogs.update', $blog) }}" method="POST">
        @csrf
        @method('PUT')

        <x-input name="title" label="Title" :value="$blog->title" />
        <x-textarea name="content" label="Content" :value="$blog->content" />
        <x-select name="blog_type" 
            label="Blog Type" 
            :options="['discussion', 'question', 'explain']" 
            :value="$blog->blog_type" 
        />
        <x-tag-input 
            name="tags" 
            label="Tags" 
            :value="$blog->tags->pluck('name')->join(', ')" 
            :whitelist="$allTags->pluck('name')" 
        />
        <button type="submit" class="btn btn-primary mt-4">Update</button>
    </form>
</x-temp-general-layout>

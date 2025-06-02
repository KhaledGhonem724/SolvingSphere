<?php

namespace App\Http\Controllers\Blogs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Blog;
use App\Models\Tag;

use League\CommonMark\CommonMarkConverter;
use Inertia\Inertia;

class BlogController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Blog::with(['owner', 'tags']);


        $user=Auth::user();
        $mineOnly = $request->boolean('my_blogs_only');

        if ($mineOnly && isset($user)) {
            $user_handle = $user->user_handle;
            $blogs->whereHas('owner', function ($query) use ($user_handle) {
                $query->where('user_handle',  $user_handle );
            });
        }

        if ($request->has('title') && $request->title != '') {
            $blogs->where('title', 'like', '%' . $request->title . '%');
        }

        // Filter by blog type
        if ($request->has('blog_type')) {
            $query->where('blog_type', $request->input('blog_type'));
        }

        // Filter by owner ID
        if ($request->has('owner_id')) {
            $query->where('owner_id', $request->input('owner_id'));
        }

        if ($request->filled('tags')) {
            $tags = $request->input('tags', []);

            $matchAll = $request->boolean('match_all_tags');

            if ($matchAll) {
                // Match all selected tags
                $query->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds)
                        ->groupBy('blogs.id')
                        ->havingRaw('COUNT(DISTINCT tags.id) = ?', [count($tagIds)]);
                });
            } else {
                // Match any of the selected tags
                $query->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                });
            }
        }

        $blogs = $query->latest()->paginate(9);
        $allTags = Tag::all();

        return Inertia::render('Blogs/Index', [
            'blogs' => $blogs,
            'allTags' => $allTags
        ]);
    }

    public function create()
    {
        $tags = Tag::all();
        $blogTypes = ['Technical', 'Personal', 'Tutorial', 'Review'];

        return Inertia::render('Blogs/Create', [
            'tags' => $tags,
            'blogTypes' => $blogTypes
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'blog_type' => 'required|in:question,discussion,explain',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $blog = Blog::create([

            ...$validated,
            'owner_id' => Auth::user()->user_handle,
        ]); // ...$validated is just merging the array $validated ,

        // tags input can be sent as json objets by Tagify
        $tagsInput = $request->input('tags', '');

        // Handle Tagify input
        if (Str::startsWith($tagsInput, '[')) {
            // Decode JSON array to get tag values
            $tagsArray = collect(json_decode($tagsInput))
                ->map(fn($tag) => trim(Str::lower($tag->value ?? '')))
                ->filter()
                ->unique();
        } else {
            // Comma-separated string case
            $tagsArray = collect(explode(',', $tagsInput))
                ->map(fn($tag) => trim(Str::lower($tag)))
                ->filter()
                ->unique();
        }

        // Create or get tags
        $tags = $tagsArray->map(function ($name) {
            return Tag::firstOrCreate(['name' => $name])->id;
        });


        // Attach tags if provided
        if (isset($validated['tags'])) {
            $blog->tags()->attach($validated['tags']);
        }

        return redirect()->route('blogs.show', $blog)
            ->with('success', 'Blog created successfully');
    }

    public function show(Blog $blog)
    {
        $blog->load([
            'owner',
            'tags',
            'comments.user',
            'reactions'
        ]);

        return Inertia::render('Blogs/Show', [
            'blog' => $blog
        ]);
    }

    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);

        $tags = Tag::all();
        $blogTypes = ['Technical', 'Personal', 'Tutorial', 'Review'];

        return Inertia::render('Blogs/Edit', [
            'blog' => $blog,
            'tags' => $tags,
            'blogTypes' => $blogTypes
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $this->authorize('update', $blog);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'blog_type' => 'required|in:question,discussion,explain',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $blog->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'blog_type' => $validated['blog_type']
        ]);

        // Sync tags
        if (isset($validated['tags'])) {
            $blog->tags()->sync($validated['tags']);
        }

        return redirect()->route('blogs.show', $blog)
            ->with('success', 'Blog updated successfully');
    }

    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);

        $blog->delete();

        return redirect()->route('blogs.index')
            ->with('success', 'Blog deleted successfully');
    }
}

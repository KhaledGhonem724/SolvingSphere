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

        // Filter by title
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        // Filter by blog type
        if ($request->has('blog_type')) {
            $query->where('blog_type', $request->input('blog_type'));
        }

        // Filter by owner ID
        if ($request->has('owner_id')) {
            $query->where('owner_id', $request->input('owner_id'));
        }

        // Filter by tags
        if ($request->has('tags')) {
            $tagIds = $request->input('tags');
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
            'title' => $validated['title'],
            'content' => $validated['content'],
            'blog_type' => $validated['blog_type'],
            'owner_id' => Auth::id()
        ]);

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

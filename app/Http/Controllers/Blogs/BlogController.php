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

use function Termwind\render;

use App\Enums\VisibilityStatus;


class BlogController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $blogs = Blog::with(['owner', 'tags']);

        $user = Auth::user();
        $filters = [
            'title' => $request->input('title', ''),
            'blog_type' => $request->input('blog_type', ''),
            'owner_id' => $request->input('owner_id', ''),
            'tags' => $request->input('tags', []),
            'match_all_tags' => $request->boolean('match_all_tags', false),
            'my_blogs_only' => $request->boolean('my_blogs_only', false)
        ];

        // Ensure tags are converted to an array and are integers
        $filters['tags'] = array_map('intval', (array)$filters['tags']);

        // Apply title filter
        if (!empty($filters['title'])) {
            $blogs->where('title', 'like', '%' . $filters['title'] . '%');
        }

        // Apply blog type filter
        if (!empty($filters['blog_type']) && $filters['blog_type'] !== 'all') {
            $blogs->where('blog_type', $filters['blog_type']);
        }

        // Apply owner filter
        if (!empty($filters['owner_id'])) {
            $blogs->whereHas('owner', function ($query) use ($filters) {
                $query->where('user_handle', $filters['owner_id']);
            });
        }

        // Apply my blogs only filter
        if ($filters['my_blogs_only'] && $user) {
            $blogs->where('owner_id', $user->user_handle);
        }

        // Apply tags filter
        if (!empty($filters['tags'])) {
            $matchAll = $filters['match_all_tags'];
            $blogs->whereHas('tags', function ($query) use ($filters, $matchAll) {
                $query->whereIn('tags.id', $filters['tags']);
            }, '=', $matchAll ? count($filters['tags']) : 1);
        }

        $allTags = Tag::orderBy('name')->get();

        $blogs = $blogs->with('owner')->latest()->paginate(10)->withQueryString();

        return Inertia::render('Blogs/Index', [
            'blogs' => $blogs,
            'allTags' => $allTags,
            'filters' => $filters
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
            'tags' => 'sometimes|nullable'
        ]);

        $blog = Blog::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'blog_type' => $validated['blog_type'],
            'owner_id' => Auth::user()->user_handle,
        ]);

        // Handle Tagify input
        $tagsInput = $request->input('tags', '');

        // Attempt to parse JSON first
        try {
            $tagsArray = collect(json_decode($tagsInput, true))
                ->map(fn($tag) => is_array($tag) ? ($tag['value'] ?? $tag['name'] ?? '') : $tag)
                ->filter(fn($tag) => !empty(trim($tag)))
                ->map(fn($tag) => trim(Str::lower($tag)))
                ->unique();
        } catch (\Exception $e) {
            // If JSON parsing fails, try comma-separated string
            $tagsArray = collect(explode(',', $tagsInput))
                ->map(fn($tag) => trim(Str::lower($tag)))
                ->filter(fn($tag) => !empty($tag))
                ->unique();
        }

        // Create or get tags
        $tagIds = $tagsArray->map(function ($name) {
            return Tag::firstOrCreate(['name' => $name])->id;
        });

        // Attach tags to the blog
        if ($tagIds->isNotEmpty()) {
            $blog->tags()->attach($tagIds);
        }

        return redirect()->route('blogs.show', $blog)
            ->with('success', 'Blog created successfully');
    }

    public function show(Blog $blog)
    {
        $blog->load([
            'owner',
            'tags',
            'reactions',
            'comments' => function ($query) {
                // Load only root comments. The 'replies' relationship in the Comment
                // model is recursive and will handle loading all nested replies.
                $query->whereNull('parent_id')
                    ->with('user', 'replies')
                    ->orderBy('created_at', 'asc');
            }
        ]);

        return Inertia::render('Blogs/Show', [
            'blog' => $blog
        ]);
    }

    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);

        // Load the blog's tags relationship
        $blog->load('tags');

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
            'tags' => 'sometimes|nullable'
        ]);

        $blog->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'blog_type' => $validated['blog_type']
        ]);

        // Handle Tagify input
        $tagsInput = $request->input('tags', '');

        // Attempt to parse JSON 
        try {
            // If it's already an array, convert to collection
            $tagsArray = is_array($tagsInput)
                ? collect($tagsInput)
                : collect(json_decode($tagsInput, true));

            $tagsArray = $tagsArray
                ->map(fn($tag) => is_array($tag) ? ($tag['value'] ?? $tag['name'] ?? $tag) : $tag)
                ->filter(fn($tag) => !empty(trim($tag)))
                ->map(fn($tag) => trim(Str::lower($tag)))
                ->unique();
        } catch (\Exception $e) {
            // Fallback to empty collection if parsing fails
            $tagsArray = collect();
        }

        // Create or get tags
        $tagIds = $tagsArray->map(function ($name) {
            return Tag::firstOrCreate(['name' => $name])->id;
        });

        // Sync tags to the blog
        if ($tagIds->isNotEmpty()) {
            $blog->tags()->sync($tagIds);
        } else {
            // If no tags, detach all existing tags
            $blog->tags()->detach();
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


    public function manage()
    {
        $blogs = Blog::with('owner')->latest()->get();
        return view('admins.staff.blogs.manage', compact('blogs'));
    }

    public function toggleStatus(Blog $blog)
    {
        $blog->status = $blog->status === VisibilityStatus::Visible
            ? VisibilityStatus::Hidden
            : VisibilityStatus::Visible;

        $blog->save();

        return back()->with('success', 'Blog status updated.');
    }

}

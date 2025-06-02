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

class BlogController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $blogs = Blog::query();

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

        if ($request->has('blog_type') && $request->blog_type != 'All') {
            $blogs->where('blog_type', $request->blog_type);
        }

        if ($request->has('owner_id') && $request->owner_id != '') {
            $blogs->where('owner_id', $request->owner_id);
        }

        if ($request->filled('tags')) {
            $tags = $request->input('tags', []);
            $matchAll = $request->boolean('match_all_tags');
        
            if ($matchAll) {
                // Match all selected tags (AND)
                $blogs->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('tags.id', $tags);
                }, '=', count($tags));
            } else {
                // Match any selected tag (OR)
                $blogs->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('tags.id', $tags);
                });
            }
        }
        $allTags = Tag::orderBy('name')->get();

        $blogs = $blogs->with('owner')->latest()->paginate(10)->withQueryString();
        

        return view('blogs.index', [
            'title' => 'Blogs',
            'blogs' => $blogs,
            'allTags' => $allTags,
        ]);
    }

    public function create()
    {
        $allTags = Tag::orderBy('name')->get();
        return view('blogs.create', compact('allTags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:blogs',
            'content' => 'required',
            'blog_type' => 'in:question,discussion,explain',
            'tags' => 'nullable|string',
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

        // Sync with blog
        $blog->tags()->sync($tags);

        return redirect()->route('blogs.index')->with('success', 'Blog created!');
    }

    public function show(Blog $blog)
    {
        // Load comments, top-level only, with their replies and commenters
        $blog->load([
            'comments' => function ($query) {
            $query->whereNull('parent_id')->latest()
                ->with(['commenter', 'replies.commenter']);
            },
            'owner'
        ]);

        // Load current user's reaction if logged in
        if (auth()->check()) {
            $userHandle = auth()->user()->user_handle;
            $blog->user_reaction = $blog->reactions()->where('user_id', $userHandle)->first();
        } else {
            $blog->user_reaction = null;
        }

        // Convert Markdown to HTML
        $converter = new CommonMarkConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
    
        $blog->parsed_content = $converter->convertToHtml($blog->content);
    
        return view('blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);
        $allTags = Tag::orderBy('name')->get();
        return view('blogs.edit', compact(['allTags','blog']));
    }

    public function update(Request $request, Blog $blog)
    {
        $this->authorize('update', $blog);

        $validated = $request->validate([
            'title' => 'required|unique:blogs,title,' . $blog->id,
            'content' => 'required',
            'blog_type' => 'in:question,discussion,explain',
            'tags' => 'nullable|string',
        ]);

        $blog->update($validated);

        $tagsInput = $validated['tags'] ?? '';

        if (Str::startsWith($tagsInput, '[')) {
            $tagsArray = collect(json_decode($tagsInput))
                ->map(fn($tag) => trim(Str::lower($tag->value ?? '')))
                ->filter()
                ->unique();
        } else {
            $tagsArray = collect(explode(',', $tagsInput))
                ->map(fn($tag) => trim(Str::lower($tag)))
                ->filter()
                ->unique();
        }

        // Create or get tags and sync in one step
        $tags = $tagsArray->map(fn($name) => Tag::firstOrCreate(['name' => $name])->id);
        $blog->tags()->sync($tags);

        return redirect()->route('blogs.show', $blog)->with('success', 'Blog updated!');
    }


    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted!');
    }
}
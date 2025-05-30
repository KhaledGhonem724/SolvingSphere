<?php

namespace App\Http\Controllers\Blogs;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Blog;


class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::query();

        if ($request->has('title') && $request->title != '') {
            $blogs->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('blog_type') && $request->blog_type != 'All') {
            $blogs->where('blog_type', $request->blog_type);
        }

        if ($request->has('owner_id') && $request->owner_id != '') {
            $blogs->where('owner_id', $request->owner_id);
        }

        $blogs = $blogs->with('owner')->latest()->paginate(10)->withQueryString();

        return view('blogs.index', [
            'title' => 'Filtered Blogs',
            'blogs' => $blogs,
        ]);
    }


    public function create()
    {
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:blogs',
            'content' => 'required',
            'blog_type' => 'in:question,discussion,explain',
        ]);

        Blog::create([
            ...$validated,
            'owner_id' => Auth::user()->user_handle,
        ]);

        return redirect()->route('blogs.index')->with('success', 'Blog created!');
    }

    public function show(Blog $blog)
    {
        $blog->load('owner', 'comments.replies');
        return view('blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);
        return view('blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $this->authorize('update', $blog);

        $validated = $request->validate([
            'title' => 'required|unique:blogs,title,' . $blog->id,
            'content' => 'required',
            'blog_type' => 'in:question,discussion,explain',
        ]);

        $blog->update($validated);
        return redirect()->route('blogs.show', $blog)->with('success', 'Blog updated!');
    }

    public function destroy(Blog $blog)
    {
        //$this->authorize('delete', $blog);
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted!');
    }
}
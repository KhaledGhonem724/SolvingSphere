<?php

namespace App\Http\Controllers\Blogs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Blog;
use App\Models\Comment;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $blog->comments()->create([
            'content' => $validated['content'],
            'commenter_id' => Auth::user()->user_handle,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return back()->with('success', 'Comment added successfully');
    }

    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('blogs.show', $comment->blog_id)
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return redirect()->route('blogs.show', $comment->blog_id)
            ->with('success', 'Comment deleted successfully.');
    }
}

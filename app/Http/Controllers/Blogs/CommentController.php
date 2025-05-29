<?php

namespace App\Http\Controllers\Blogs;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'content' => 'required',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        Comment::create([
            ...$validated,
            'commenter_id' => Auth::user()->user_handle,
        ]);

        return redirect()->back()->with('success', 'Comment posted!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted!');
    }
}

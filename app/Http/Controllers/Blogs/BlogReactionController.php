<?php

namespace App\Http\Controllers\Blogs;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Pivots\BlogReaction;
use App\Models\Blog;

class BlogReactionController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Blog $blog)
    {
        $request->validate([
            'value' => 'required|in:1,-1',
        ]);
    
        $user = auth()->user();
        $value = (int) $request->value;
    
        $existing = $blog->reactions()->where('user_id', $user->user_handle)->first();
    
        if ($existing) {
            if ($existing->value === $value) {
                // Toggle off (remove reaction)
                $existing->delete();
                $blog->decrement('score', $value);
            } else {
                // Change vote
                $blog->increment('score', $value - $existing->value);
                $existing->update(['value' => $value]);
            }
        } else {
            // New reaction
            $blog->reactions()->create([
                'user_id' => $user->user_handle,
                'value' => $value,
            ]);
            $blog->increment('score', $value);
        }
    
        return back();
    }
    


}

<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{


    // Determine whether the user can update the model.
    public function update(User $user, Comment $comment): bool
    {
        return $user->user_handle === $comment->commenter_id;
    }

    // Determine whether the user can delete the model.
    public function delete(User $user, Comment $comment): bool
    {
        return $user->user_handle === $comment->commenter_id;
    }
}

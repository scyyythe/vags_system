<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{


    public function modify(User $user, Post $post): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        return $user->id === $post->user_id
            ? Response::allow()
            : Response::deny('You do not own this post.');
    }

    public function updateStatus(User $user): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Only administrator can update the status of this exhibit.');
    }
}

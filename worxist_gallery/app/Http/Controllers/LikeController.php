<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likePost(Request $request, Post $post)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        } else if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        } else if ($user->likes()->where('post_id', $post->post_id)->exists()) {
            return response()->json(['message' => 'You have already liked this post'], 400);
        }

        $like = $user->likes()->create(['post_id' => $post->post_id]);

        return response()->json(['message' => 'Post liked successfully', 'like' => $like], 200);
    }

    // Remove like
    public function removeLike(Request $request, Post $post)
    {
        $user = $request->user();
        $like = $user->likes()->where('post_id', $post->post_id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        } else if (!$like) {
            return response()->json(['message' => 'You have not liked this post'], 400);
        }

        // Delete the like
        $like->delete();

        return response()->json(['message' => 'Post like removed successfully'], 200);
    }
}

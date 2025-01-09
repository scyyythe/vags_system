<?php

namespace App\Http\Controllers;

use App\Models\Saved;
use App\Models\Post;
use Illuminate\Http\Request;

class SavedController extends Controller
{
    // Add save
    public function savePost(Request $request, Post $post)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        } else if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        } else if ($user->saved_post()->where('post_id', $post->post_id)->exists()) {
            return response()->json(['message' => 'You have already saved this post'], 400);
        }

        $save = $user->saved_post()->create(['post_id' => $post->post_id]);

        return response()->json(['message' => 'Post saved successfully', 'save' => $save], 200);
    }

    // Remove save
    public function removeSave(Request $request, Post $post)
    {
        $user = $request->user();
        $save = $user->saved_post()->where('post_id', $post->post_id)->first();

        if (!$save) {
            return response()->json(['message' => 'You have not saved this post'], 400);
        }

        $save->delete();

        return response()->json(['message' => 'Post save removed successfully'], 200);
    }
}

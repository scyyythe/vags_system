<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Post;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Add favorite
    public function favoritePost(Request $request, Post $post)
    {
        $user = $request->user();
        if ($user->favorites()->where('post_id', $post->post_id)->exists()) {
            return response()->json(['message' => 'You have already favorited this post'], 400);
        }

        $favorite = $user->favorites()->create(['post_id' => $post->post_id]);

        return response()->json(['message' => 'Post favorited successfully', 'favorite' => $favorite], 200);
    }

    // Remove favorite
    public function removeFavorite(Request $request, Post $post)
    {
        $user = $request->user();
        $favorite = $user->favorites()->where('post_id', $post->post_id)->first();

        if (!$favorite) {
            return response()->json(['message' => 'You have not favorited this post'], 400);
        }

        // Delete the favorite
        $favorite->delete();

        return response()->json(['message' => 'Post favorite removed successfully'], 200);
    }
}

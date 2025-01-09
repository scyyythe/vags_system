<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        $followerId = Auth::id();
        if (!$followerId) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $request->validate([
            'following_id' => 'required|exists:users,id',
        ]);

        $followingId = $request->following_id;

        $existingFollow = Follow::where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->first();

        if ($existingFollow) {
            return response()->json(['message' => 'Already following this user.'], 400);
        }

        Follow::create([
            'follower_id' => $followerId,
            'following_id' => $followingId,
        ]);

        return response()->json(['message' => 'Followed successfully.'], 201);
    }

    public function unfollow(Request $request)
    {
        $followerId = Auth::id();
        if (!$followerId) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $request->validate([
            'following_id' => 'required|exists:users,id',
        ]);

        $followingId = $request->following_id;

        $follow = Follow::where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->first();

        if (!$follow) {
            return response()->json(['message' => 'You are not following this user.'], 400);
        }

        // Delete the follow record
        Follow::where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->delete();

        return response()->json(['message' => 'Unfollowed successfully.'], 200);
    }
}

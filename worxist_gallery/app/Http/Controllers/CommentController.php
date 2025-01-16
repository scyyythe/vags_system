<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request, Post $post)
    {
        // Validate that 'comment_text' is required and a string
        $validated = $request->validate([
            'comment_text' => 'required|string',
        ]);

        // Retrieve the authenticated user
        $user = $request->user(); // This retrieves the authenticated user

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Check if the post exists
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Create a new comment
        $comment = $user->comments()->create([
            'post_id' => $post->post_id,
            'comment_text' => $validated['comment_text'],  // Use validated input for comment_text
        ]);

        // Send a notification
        $notificationData = [
            'user_id' => $post->user_id,
            'message' => $user->name . ' commented on your post: "' . $post->title . '"',
            'type' => 'comment',
            'is_read' => false,
            'post_id' => $post->post_id,
        ];

        Notification::create($notificationData);
        Log::info($request->all());

        return response()->json(['message' => 'Commented successfully', 'comment' => $comment], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function removeComment(Request $request, Post $post)
    {
        $user = $request->user();
        $comment = $user->comments()->where('post_id', $post->post_id)->first();

        if (!$comment) {
            return response()->json(['message' => 'You have not commented on this post'], 400);
        }

        $comment->delete();

        return response()->json(['message' => 'Removed comment successfully'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [new Middleware('auth:sanctum', except: ['index', 'show'])];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validate incoming data
        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'category' => ['required'],
            'image' => ['required', 'max:1000']
            // 'image' => ['required', 'file', 'max:1000', 'mimes:jpeg,png,jpg']
        ]);

        // Handle file upload and store it
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts_images', 'public');
            $fields['image'] = $imagePath;
        }

        // Create the post with the user association
        $post = $request->user()->posts()->create($fields);


        return $post;
    }



    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify', $post);
        // Validate incoming data
        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'category' => ['required'],
            'image' => ['required', 'max:1000']
            // 'image' => ['required', 'file', 'max:1000', 'mimes:jpeg,png,jpg']
        ]);

        // Handle file upload and store it
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts_images', 'public');
            $fields['image'] = $imagePath;
        }
        $post->update($fields);

        return $post;
    }

    // accepet Post by admin
    public function updateStatus(Request $request, Post $post)
    {
        // Authorize the admin to update the status
        Gate::authorize('updateStatus', $post);

        // Validate only the exhibit_status field
        $fields = $request->validate([
            'post_status' => ['required', 'in:Accepted,Rejected,Pending'], // Specify valid statuses
        ]);

        // Update the exhibit's status
        $post->update($fields);

        return response()->json(['message' => 'Post status updated successfully.', 'post' => $post], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return  $post;
    }


    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}

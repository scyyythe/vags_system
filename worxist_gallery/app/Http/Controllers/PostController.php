<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        $fields = $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'category' => ['required'],
            'image' => ['required', 'file', 'max:2000', 'mimes:jpeg,png,jpg'],
        ]);
        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->put('posts_images', $request->image);
            $fields['image'] = $path;
        }

        $post = $request->user()->posts()->create($fields);

        return response()->json([
            'post' => $post,
            'message' => 'Post created successfully',
        ], 201);
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
            'post_status' => ['required', 'in:Accepted,Rejected,Pending'],
        ]);

        // Update the exhibit's status
        $post->update($fields);
        $message = '';
        if ($fields['post_status'] == 'Accepted') {
            $message = 'Your post has been accepted by the administrator.';
        } elseif ($fields['post_status'] == 'Rejected') {
            $message = 'Your post has been rejected by the administrator.';
        }

        Notification::create([
            'user_id' => $post->user_id,
            'message' => $message,
            'type' => 'status_update',
            'is_read' => false,
        ]);
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

    public function getUserPosts(Request $request)
    {

        $user = $request->user();
        $posts = $user->posts;
        return response()->json($posts);
    }
    //get post by user id
    public function getPostByUser(User $user)
    {
        $posts = $user->posts;
        return $posts;
    }

    public function getUserLikedPosts()
    {
        // Assuming a relationship is set up between User and Post via likes
        $likedPosts = Auth::user()->likedPosts;

        return response()->json($likedPosts);
    }

    public function getUserSavedPosts()
    {
        // Assuming a relationship is set up between User and Post via saved
        $savedPosts = Auth::user()->savedPosts;

        return response()->json($savedPosts);
    }

    public function getUserFavoritedPosts()
    {
        // Assuming a relationship is set up between User and Post via favorites
        $favoritedPosts = Auth::user()->favoritedPosts;

        return response()->json($favoritedPosts);
    }
    public function getUserFavoritesAndLikes(Request $request)
    {
        $user = $request->user();

        $likedPosts = $user->likedPosts;
        $favoritedPosts = $user->favoritedPosts;
        $savedPosts = $user->savedPosts;

        $posts = $likedPosts->merge($favoritedPosts)->merge($savedPosts)->unique('post_id');


        return $posts->map(function ($post) {
            return [
                'post_id' => $post->post_id,
                'title' => $post->title,
                'description' => $post->description,
                'created_at' => $post->created_at,
            ];
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Exhibit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use App\Models\Notification;


class ExhibitController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [new Middleware('auth:sanctum', except: ['index', 'show'])];
    }

    public function index()
    {
        $exhibits = Exhibit::all(); // Fetch all exhibits
        return response()->json($exhibits, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!$request->user()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Validate the incoming request data
        $fields = $request->validate([
            'exhibit_title' => ['required', 'max:255'],
            'exhibit_description' => ['required', 'max:255'],
            'exhibit_date' => ['required'],
            'exhibit_type' => ['required'],
            'post_ids' => ['required', 'array', 'min:1', 'max:10'], // Validate the post_ids
            'post_ids.*' => ['exists:posts,post_id'], // Ensure each post exists
        ]);

        // Create the exhibit
        $exhibit = $request->user()->exhibits()->create([
            'exhibit_title' => $fields['exhibit_title'],
            'exhibit_description' => $fields['exhibit_description'],
            'exhibit_date' => $fields['exhibit_date'],
            'exhibit_type' => $fields['exhibit_type'],
            'exhibit_status' => 'Pending'
        ]);

        // Attach the selected posts (artworks) to the exhibit using the pivot table
        $exhibit->posts()->attach($fields['post_ids']);

        // Retrieve the exhibit with the attached posts using eager loading
        $exhibitWithPosts = $exhibit->load('posts');

        // Return the newly created exhibit along with the attached artworks
        return response()->json([
            'message' => 'Exhibit created successfully with artworks attached.',
            'exhibit' => $exhibitWithPosts
        ]);
    }




    /**
     * Display the specified resource.
     */
    public function show(Exhibit $exhibit)
    {
        return  $exhibit;
    }



    public function update(Request $request, Exhibit $exhibit)
    {
        Gate::authorize('modify', $exhibit);

        $fields = $request->validate([
            'exhibit_title' => ['required', 'max:255'],
            'exhibit_description' => ['required', 'max:255'],
            'exhibit_date' => ['required'],
            'exhibit_type' => ['required'],
            'exhibit_status'
        ]);

        $exhibit->update($fields);
        return $exhibit;
    }

    // This is for the ogranizer they can Accept and Reject the Exhibit
    public function updateStatus(Request $request, Exhibit $exhibit)
    {
        // Authorize the admin to update the status
        Gate::authorize('updateStatus', $exhibit);

        // Validate only the exhibit_status field
        $fields = $request->validate([
            'exhibit_status' => ['required', 'in:Accepted,Rejected,Pending'], // Specify valid statuses
        ]);

        // Update the exhibit's status
        $exhibit->update($fields);
        $message = '';
        if ($fields['exhibit_status'] == 'Accepted') {
            $message = 'Your exhibit has been accepted by the organizer.';
        } elseif ($fields['exhibit_status'] == 'Rejected') {
            $message = 'Your exhibit has been rejected by the organizer.';
        }

        Notification::create([
            'user_id' => $exhibit->user_id,
            'message' => $message,
            'type' => 'status_update',
            'is_read' => false,
        ]);
        return response()->json(['message' => 'Exhibit status updated successfully.', 'exhibit' => $exhibit], 200);
    }


    public function destroy(Exhibit $exhibit)
    {
        Gate::authorize('modify', $exhibit);
        $exhibit->delete();

        return response()->json(['message' => 'Post Deleted']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Exhibit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use App\Models\Notification;
use App\Models\ArtworksExhibit;
use Illuminate\Support\Facades\Log;


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
            'exhibit_date' => ['required', 'date'],
            'exhibit_type' => ['required'],
            'post_ids' => ['required', 'array', 'min:1', 'max:10'], // Validate the post_ids
            'post_ids.*' => ['exists:posts,post_id'], // Ensure each post exists
            'collaborators' => ['nullable', 'array', 'max:5'], // Allow collaborators, with a maximum of 5
            'collaborators.*' => ['exists:users,id'], // Ensure collaborators exist
        ]);

        // Check if the user is trying to add themselves as a collaborator
        if (isset($fields['collaborators']) && in_array($request->user()->id, $fields['collaborators'])) {
            return response()->json(['error' => 'You cannot add yourself as a collaborator.'], 422);
        }

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

        // If the exhibit type is "collaborator," handle collaborators
        if ($fields['exhibit_type'] === 'collaborator') {
            if (!isset($fields['collaborators']) || count($fields['collaborators']) === 0) {
                return response()->json(['error' => 'At least 1 collaborator is required for a collaborator exhibit.'], 422);
            }

            // Attach collaborators to the exhibit
            foreach ($fields['collaborators'] as $collaboratorId) {
                $exhibit->collaborators()->create([
                    'user_id' => $collaboratorId,
                ]);
            }
        }

        // Retrieve the exhibit with the attached posts and collaborators using eager loading
        $exhibitWithRelations = $exhibit->load(['posts', 'collaborators']);

        // Return the newly created exhibit along with the attached artworks and collaborators
        return response()->json([
            'message' => 'Exhibit created successfully with artworks and collaborators attached.',
            'exhibit' => $exhibitWithRelations
        ]);
    }

    //for collaboratos
    public function attachArtworks(Request $request, $exhibitId)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Validate the incoming request data
        $fields = $request->validate([
            'post_ids' => ['required', 'array', 'min:1', 'max:5'], // Max 5 artworks per request
            'post_ids.*' => ['exists:posts,post_id'], // Ensure the artworks exist
        ]);

        // Retrieve the exhibit
        $exhibit = Exhibit::findOrFail($exhibitId);

        // Check if the user is a collaborator in this exhibit
        $isCollaborator = $exhibit->collaborators()->where('user_id', $request->user()->id)->exists();
        if (!$isCollaborator) {
            return response()->json(['error' => 'You are not a collaborator for this exhibit.'], 403);
        }

        // Count the current artworks attached by this collaborator
        $currentArtworks = ArtworksExhibit::where('exhibit_id', $exhibitId)
            ->whereHas('post', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->count();

        // Check if the new additions exceed the limit of 5 artworks per collaborator
        if ($currentArtworks + count($fields['post_ids']) > 5) {
            return response()->json(['error' => 'You can attach a maximum of 5 artworks per exhibit.'], 422);
        }

        // Attach the artworks to the exhibit
        foreach ($fields['post_ids'] as $postId) {
            ArtworksExhibit::create([
                'exhibit_id' => $exhibitId,
                'post_id' => $postId,
            ]);
        }

        // Fetch the details of the attached posts
        $attachedPosts = \App\Models\Post::whereIn('post_id', $fields['post_ids'])->get();

        return response()->json([
            'message' => 'Artworks successfully attached to the exhibit.',
            'exhibit_id' => $exhibitId,
            'attached_posts' => $attachedPosts
        ]);
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

    /**
     * Display the specified resource.
     */
    public function show($exhibitId)
    {
        // Retrieve the exhibit by ID, with eager loading for collaborators and their posts
        $exhibit = Exhibit::with(['collaborators.posts', 'posts'])->findOrFail($exhibitId);

        // Get the details of each collaborator, including their posts (artworks)
        $collaborators = $exhibit->collaborators->isEmpty() ? [] : $exhibit->collaborators->map(function ($collaborator) {
            return [
                'user_id' => $collaborator->user_id,
                'name' => $collaborator->user->name,
                'email' => $collaborator->user->email,
                'posts' => $collaborator->posts->isEmpty() ? [] : $collaborator->posts->map(function ($post) {
                    return [
                        'post_id' => $post->post_id,
                        'title' => $post->title,
                        'description' => $post->description,
                        'created_at' => $post->created_at,
                    ];
                })
            ];
        });


        // Get the details of the exhibit's posts (artworks)
        $posts = $exhibit->posts->map(function ($post) {
            return [
                'post_id' => $post->post_id,
                'title' => $post->title,
                'description' => $post->description,
                'created_at' => $post->created_at,
            ];
        });

        return response()->json([
            'exhibit' => [
                'exhibit_id' => $exhibit->exhibit_id,
                'exhibit_title' => $exhibit->exhibit_title,
                'exhibit_description' => $exhibit->exhibit_description,
                'exhibit_date' => $exhibit->exhibit_date,
                'exhibit_type' => $exhibit->exhibit_type,
                'exhibit_status' => $exhibit->exhibit_status,
            ],
            'collaborators' => $collaborators,  // Returning collaborators with their posts
            'posts' => $posts,  // Returning the exhibit's posts (artworks)
        ]);
    }


    // Pending Exhibits
    public function getPendingExhibits()
    {
        // Retrieve all pending exhibits with eager loading for collaborators and their posts
        $pendingExhibits = Exhibit::with(['collaborators.posts', 'posts'])
            ->where('exhibit_status', 'pending')  // Filter by pending status
            ->get();

        // Map the results to include the collaborators and their posts
        $exhibits = $pendingExhibits->map(function ($exhibit) {
            // Get the details of each collaborator, including their posts
            $collaborators = $exhibit->collaborators->isEmpty() ? [] : $exhibit->collaborators->map(function ($collaborator) {
                return [
                    'user_id' => $collaborator->user_id,
                    'name' => $collaborator->user->name,
                    'email' => $collaborator->user->email,
                    'posts' => $collaborator->posts->isEmpty() ? [] : $collaborator->posts->map(function ($post) {
                        return [
                            'post_id' => $post->post_id,
                            'title' => $post->title,
                            'description' => $post->description,
                            'created_at' => $post->created_at,
                        ];
                    })
                ];
            });

            // Get the details of the exhibit's posts (artworks)
            $posts = $exhibit->posts->map(function ($post) {
                return [
                    'post_id' => $post->post_id,
                    'title' => $post->title,
                    'description' => $post->description,
                    'created_at' => $post->created_at,
                ];
            });

            // Return the exhibit's data, including collaborators and posts
            return [
                'exhibit' => [
                    'exhibit_id' => $exhibit->exhibit_id,
                    'exhibit_title' => $exhibit->exhibit_title,
                    'exhibit_description' => $exhibit->exhibit_description,
                    'exhibit_date' => $exhibit->exhibit_date,
                    'exhibit_type' => $exhibit->exhibit_type,
                    'exhibit_status' => $exhibit->exhibit_status,
                ],
                'collaborators' => $collaborators,  // Returning collaborators with their posts
                'posts' => $posts,  // Returning the exhibit's posts (artworks)
            ];
        });

        return response()->json($exhibits);
    }

    //Accepted Exhibits
    public function getAcceptedExhibits()
    {
        // Retrieve all pending exhibits with eager loading for collaborators and their posts
        $pendingExhibits = Exhibit::with(['collaborators.posts', 'posts'])
            ->where('exhibit_status', 'accepted')  // Filter by pending status
            ->get();

        // Map the results to include the collaborators and their posts
        $exhibits = $pendingExhibits->map(function ($exhibit) {
            // Get the details of each collaborator, including their posts
            $collaborators = $exhibit->collaborators->isEmpty() ? [] : $exhibit->collaborators->map(function ($collaborator) {
                return [
                    'user_id' => $collaborator->user_id,
                    'name' => $collaborator->user->name,
                    'email' => $collaborator->user->email,
                    'posts' => $collaborator->posts->isEmpty() ? [] : $collaborator->posts->map(function ($post) {
                        return [
                            'post_id' => $post->post_id,
                            'title' => $post->title,
                            'description' => $post->description,
                            'created_at' => $post->created_at,
                        ];
                    })
                ];
            });

            // Get the details of the exhibit's posts (artworks)
            $posts = $exhibit->posts->map(function ($post) {
                return [
                    'post_id' => $post->post_id,
                    'title' => $post->title,
                    'description' => $post->description,
                    'created_at' => $post->created_at,
                ];
            });

            // Return the exhibit's data, including collaborators and posts
            return [
                'exhibit' => [
                    'exhibit_id' => $exhibit->exhibit_id,
                    'exhibit_title' => $exhibit->exhibit_title,
                    'exhibit_description' => $exhibit->exhibit_description,
                    'exhibit_date' => $exhibit->exhibit_date,
                    'exhibit_type' => $exhibit->exhibit_type,
                    'exhibit_status' => $exhibit->exhibit_status,
                ],
                'collaborators' => $collaborators,  // Returning collaborators with their posts
                'posts' => $posts,  // Returning the exhibit's posts (artworks)
            ];
        });

        return response()->json($exhibits);
    }


    public function getOngoing()
    {
        // Retrieve all pending exhibits with eager loading for collaborators and their posts
        $pendingExhibits = Exhibit::with(['collaborators.posts', 'posts'])
            ->where('exhibit_status', 'ongoing')  // Filter by pending status
            ->get();

        // Map the results to include the collaborators and their posts
        $exhibits = $pendingExhibits->map(function ($exhibit) {
            // Get the details of each collaborator, including their posts
            $collaborators = $exhibit->collaborators->isEmpty() ? [] : $exhibit->collaborators->map(function ($collaborator) {
                return [
                    'user_id' => $collaborator->user_id,
                    'name' => $collaborator->user->name,
                    'email' => $collaborator->user->email,
                    'posts' => $collaborator->posts->isEmpty() ? [] : $collaborator->posts->map(function ($post) {
                        return [
                            'post_id' => $post->post_id,
                            'title' => $post->title,
                            'description' => $post->description,
                            'created_at' => $post->created_at,
                        ];
                    })
                ];
            });

            // Get the details of the exhibit's posts (artworks)
            $posts = $exhibit->posts->map(function ($post) {
                return [
                    'post_id' => $post->post_id,
                    'title' => $post->title,
                    'description' => $post->description,
                    'created_at' => $post->created_at,
                ];
            });

            // Return the exhibit's data, including collaborators and posts
            return [
                'exhibit' => [
                    'exhibit_id' => $exhibit->exhibit_id,
                    'exhibit_title' => $exhibit->exhibit_title,
                    'exhibit_description' => $exhibit->exhibit_description,
                    'exhibit_date' => $exhibit->exhibit_date,
                    'exhibit_type' => $exhibit->exhibit_type,
                    'exhibit_status' => $exhibit->exhibit_status,
                ],
                'collaborators' => $collaborators,  // Returning collaborators with their posts
                'posts' => $posts,  // Returning the exhibit's posts (artworks)
            ];
        });

        return response()->json($exhibits);
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

<?php

namespace App\Http\Controllers;

use App\Models\Exhibit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;


class ExhibitController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [new Middleware('auth:sanctum', except: ['index', 'show'])];
    }

    public function index()
    {
        Exhibit::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if the user is authenticated
        if (!$request->user()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $fields = $request->validate([
            'exhibit_title' => ['required', 'max:255'],
            'exhibit_description' => ['required', 'max:255'],
            'exhibit_date' => ['required'],
            'exhibit_type' => ['required'],
        ]);

        $exhibit = $request->user()->exhibits()->create([
            'exhibit_title' => $fields['exhibit_title'],
            'exhibit_description' => $fields['exhibit_description'],
            'exhibit_date' => $fields['exhibit_date'],
            'exhibit_type' => $fields['exhibit_type'],
            'exhibit_status' => 'Pending'
        ]);

        return $exhibit;
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

        return response()->json(['message' => 'Exhibit status updated successfully.', 'exhibit' => $exhibit], 200);
    }


    public function destroy(Exhibit $exhibit)
    {
        Gate::authorize('modify', $exhibit);
        $exhibit->delete();

        return response()->json(['message' => 'Post Deleted']);
    }
}

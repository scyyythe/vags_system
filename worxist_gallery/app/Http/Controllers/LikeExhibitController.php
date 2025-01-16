<?php

namespace App\Http\Controllers;

use App\Models\Exhibit;
use App\Models\LikeExhibit;
use Illuminate\Http\Request;
use App\Models\Notification;

class LikeExhibitController extends Controller
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

    public function likeExhibit(Request $request, Exhibit $exhibit)
    {

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        } else if (!$exhibit) {
            return response()->json(['message' => 'Exhibit not found'], 404);
        } else if ($user->exhibitlike()->where('exhibit_id', $exhibit->exhibit_id)->exists()) {
            return response()->json(['message' => 'You have already liked this exhibit'], 400);
        }

        $like = $user->exhibitlike()->create(['exhibit_id' => $exhibit->exhibit_id]);
        $notificationData = [
            'user_id' => $exhibit->user_id,
            'message' => $user->name . ' liked your exhibit: "' . $exhibit->exhibit_title . '"',
            'type' => 'like',
            'is_read' => false,
            'exhibit_id' => $exhibit->exhibit_id,
        ];

        Notification::create($notificationData);

        return response()->json(['message' => 'Exhibit liked successfully', 'like' => $like], 200);
    }


    public function removeLike(Request $request, Exhibit $exhibit)
    {

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $like = $user->exhibitlike()->where('exhibit_id', $exhibit->exhibit_id)->first();

        if (!$like) {
            return response()->json(['message' => 'You have not liked this exhibit'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Exhibit like removed successfully'], 200);
    }
}

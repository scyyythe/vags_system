<?php

namespace App\Http\Controllers;

use App\Models\ArtworksExhibit;
use App\Models\Post;
use App\Models\Exhibit;
use Illuminate\Http\Request;

class ArtworksExhibitController extends Controller
{
    /**
     * Attach an artwork (post) to an exhibit.
     */
    public function attachArtworkToExhibit(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'exhibit_id' => 'required|exists:exhibits,exhibit_id',
            'post_id' => 'required|exists:posts,post_id',
        ]);

        // Create the record in the pivot table
        $artworkExhibit = ArtworksExhibit::create([
            'exhibit_id' => $request->exhibit_id,
            'post_id' => $request->post_id,
        ]);

        return response()->json([
            'message' => 'Artwork successfully attached to the exhibit.',
            'data' => $artworkExhibit,
        ]);
    }

    /**
     * Get all artworks for a specific exhibit.
     */

    public function getArtworksByExhibit($exhibitId)
    {

        $exhibit = Exhibit::with('posts')->find($exhibitId);
        if (!$exhibit) {
            return response()->json([
                'error' => 'Exhibit not found'
            ], 404);
        }

        return response()->json([
            'exhibit' => $exhibit,
        ]);
    }




    /**
     * Remove an artwork from an exhibit.
     */
    public function detachArtworkFromExhibit($exhibitId, $postId)
    {
        // Check if the exhibit exists
        $exhibit = Exhibit::find($exhibitId);
        if (!$exhibit) {
            return response()->make('Exhibit not found.', 404);
        }

        // Check if the post exists
        $post = Post::find($postId);
        if (!$post) {
            return response()->make('Post not found.', 404);
        }

        // Check if the artwork is already attached to the exhibit
        $artworkExhibit = ArtworksExhibit::where('exhibit_id', $exhibitId)
            ->where('post_id', $postId)
            ->first();

        if (!$artworkExhibit) {
            return response()->make('Artwork not attached to this exhibit.', 404);
        }

        // Detach the post from the exhibit
        $artworkExhibit->delete();


        return response()->make('Artwork successfully detached from the exhibit.', 200);
    }
}

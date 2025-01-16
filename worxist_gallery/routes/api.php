<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExhibitController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SavedController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ArtworksExhibitController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LikeExhibitController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('posts', PostController::class);
Route::apiResource('exhibits', ExhibitController::class);


// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// with auth sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);


    // Upload a post
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{posts}', [PostController::class, 'update']);
    Route::delete('posts/{posts}', [PostController::class, 'destroy']);
    Route::get('posts/{posts}', [PostController::class, 'show']);
    // Update Posts Status by Admin
    Route::patch('posts/{post}/status', [PostController::class, 'updateStatus']);


    // Get POst by a specific user
    Route::get('/user/posts', [PostController::class, 'getUserPosts']);
    Route::get('/user/{user}/posts', [PostController::class, 'getPostByUser']);
    // Request an Exhibit
    Route::post('exhibits/', [ExhibitController::class, 'store']);
    Route::put('exhibits/{exhibits}', [ExhibitController::class, 'update']);
    Route::delete('exhibits/{exhibits}', [ExhibitController::class, 'destroy']);
    Route::get('exhibits/{exhibits}', [ExhibitController::class, 'show']);

    //like exhibit
    Route::post('/exhibits/{exhibit}/like', [LikeExhibitController::class, 'likeExhibit']);
    Route::delete('/exhibits/{exhibit}/like', [LikeExhibitController::class, 'removeLike']);

    // Update Exhibit Status by Organizer
    Route::patch('exhibits/{exhibit}/status', [ExhibitController::class, 'updateStatus']);


    // Include the artwork in the exhibit
    Route::post('/exhibits/artworks', [ArtworksExhibitController::class, 'attachArtworkToExhibit']);
    Route::get('/exhibits/{exhibitId}/artworks', [ArtworksExhibitController::class, 'getArtworksByExhibit']);
    Route::delete('/exhibits/{exhibitId}/artworks/{postId}', [ArtworksExhibitController::class, 'detachArtworkFromExhibit']);
    //for collaborator
    Route::post('/exhibits/{exhibitId}/attach-artworks', [ExhibitController::class, 'attachArtworks']);


    //get pending and accepted 
    Route::get('/pending-exhibits', [ExhibitController::class, 'getPendingExhibits']);
    Route::get('/accepted-exhibits', [ExhibitController::class, 'getAcceptedExhibits']);
    Route::get('/ongoing-exhibits', [ExhibitController::class, 'getOngoing']);

    // User follow and unfollow
    Route::post('/follow', [FollowController::class, 'follow']);
    Route::delete('/unfollow', [FollowController::class, 'unfollow']);


    // Like routes
    Route::post('/posts/{post}/like', [LikeController::class, 'likePost']);
    Route::delete('/posts/{post}/like', [LikeController::class, 'removeLike']);

    // Save routes
    Route::post('/posts/{post}/save', [SavedController::class, 'savePost']);
    Route::delete('/posts/{post}/save', [SavedController::class, 'removeSave']);

    // Favorite routes
    Route::post('/posts/{post}/favorite', [FavoriteController::class, 'favoritePost']);
    Route::delete('/posts/{post}/favorite', [FavoriteController::class, 'removeFavorite']);

    // Comment routes
    Route::post('/posts/{post}/comment', [CommentController::class, 'store']);
    Route::delete('/posts/{post}/comment', [CommentController::class, 'removeComment']);


    Route::get('/user/posts/liked', [PostController::class, 'getUserLikedPosts']);
    Route::get('/user/posts/saved', [PostController::class, 'getUserSavedPosts']);
    Route::get('/user/posts/favorited', [PostController::class, 'getUserFavoritedPosts']);
    Route::get('/user/posts/favorited', [PostController::class, 'getUserComments']);

    // NOtifcaiton

    Route::get('/notifications', [NotificationController::class, 'getUserNotifications']);
    Route::put('/notifications/{notificationId}/read', [NotificationController::class, 'markNotificationAsRead']);
});

<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExhibitController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('posts', PostController::class);
Route::apiResource('exhibits', ExhibitController::class);


// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Upload a post
Route::post('posts/', [PostController::class, 'store']);
Route::put('posts/{posts}', [PostController::class, 'update']);
Route::delete('posts/{posts}', [PostController::class, 'destroy']);
Route::get('posts/{posts}', [PostController::class, 'show']);

// Update Posts Status by Admin
Route::patch('posts/{post}/status', [PostController::class, 'updateStatus']);

// Request an Exhibit
Route::post('exhibits/', [ExhibitController::class, 'store']);
Route::put('exhibits/{exhibits}', [ExhibitController::class, 'update']);
Route::delete('exhibits/{exhibits}', [ExhibitController::class, 'destroy']);
Route::get('exhibits/{exhibits}', [ExhibitController::class, 'show']);


// Update Exhibit Status by Organizer
Route::patch('exhibits/{exhibit}/status', [ExhibitController::class, 'updateStatus']);

<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/store', [PostController::class, 'store']);
Route::get('/posts', [PostController::class, 'index']);

// Add a route for user's posts
Route::get('/users/{userId}/posts', [PostController::class, 'getUserPosts']);

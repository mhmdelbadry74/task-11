<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {

        $image = null;
        if ($request->hasFile('image')) {
            // Get the file from the request
            $file = $request->file('image');

            // Generate a unique name for the file
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Store the file in the 'images' directory within the 'public' disk
            $file->storeAs('images', $fileName, 'public');

            // Set the image variable to the file's path
            $image = 'images/' . $fileName;
        }
        $post = Post::create($request->validated() + ['user_id' => 1 , 'image' => $image]);
        $post->save();
        return response()->json([
            'message' => 'Post created successfully!',
            'post' => $post->load('user'), // Eager load user data
        ]);
    }

    public function index()
    {
        $posts = Post::with('user')->latest()->get();

        return response()->json($posts);
    }

    // Add a route for user's posts (adjust based on your authentication setup)
    public function getUserPosts($userId)
    {
        $posts = Post::with('user')->where('user_id', $userId)->latest()->get();

        return response()->json($posts);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Get all posts
     * 
     * This function retrieves all posts from the database and returns them as JSON.
     * It's connected to a GET route, typically something like GET /api/posts
     * 
     * @return \Illuminate\Http\JsonResponse JSON response containing all posts
     */
    public function index()
    {
        // Retrieve all posts from the database
        $data = Post::all();
        
        // Return JSON response with success status and all post data
        return response()->json([
            'status' => 'success gets all data',
            'code' => 200,
            'data' => $data
        ]);
    }

    /**
     * Create a new post
     * 
     * This function validates and stores a new post in the database.
     * It's connected to a POST route, typically something like POST /api/posts
     * 
     * @param Request $request HTTP request containing post data
     * @return \Illuminate\Http\JsonResponse JSON response with the created post or validation errors
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'gambar' => 'required|string',
            'author' => 'required|string|max:100',
            'tahun' => 'required|integer',
        ]);

        // If validation fails, return error response with validation messages
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed post data',
                'code' => 422, // HTTP 422 Unprocessable Entity
                'errors' => $validator->errors()
            ], 422);
        }

        // Create a new post with the validated data
        $post = Post::create($request->all());

        // Return success response with the created post data
        return response()->json([
            'status' => 'success',
            'code' => 201, // HTTP 201 Created
            'data' => $post
        ], 201);
    }
}
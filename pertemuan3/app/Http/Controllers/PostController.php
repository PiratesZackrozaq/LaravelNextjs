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

    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => 'Failed get by id',
                'code' => 404,
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'Success get by id',
            'code' => 200,
            'data' => $post
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => 'Failed update by id',
                'code' => 404,
                'data' => null
            ], 404);
        }

        $validator = $request->validate( [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required',
            'gambar' => 'sometimes|required|string',
            'author' => 'sometimes|required|string|max:100',
            'tahun' => 'sometimes|required|integer',
        ]);

        $post->update($validator);

        return response()->json([
            'status' => 'Success update post',
            'code' => 200,
            'data' => $post
        ]);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => 'Failed get by id',
                'code' => 404,
                'data' => null
            ], 404);
        }

        $post->delete();

        return response()->json([
            'status' => 'Success delete post',
            'code' => 200,
            'data' => null
        ]);
    }
}
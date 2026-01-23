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
     * This is the "R" in CRUD (Read operation)
     * 
     * @return \Illuminate\Http\JsonResponse JSON response containing all posts
     */
    public function index()
    {
        // Retrieve all posts from the database using Eloquent ORM
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
     * This is the "C" in CRUD (Create operation)
     * 
     * @param Request $request HTTP request containing post data
     * @return \Illuminate\Http\JsonResponse JSON response with the created post or validation errors
     */
    public function store(Request $request)
    {
        // Validate the incoming request data using Laravel's Validator
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
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

        $path = $request->file('gambar')->store('posts', 'public');

        // Create a new post with the validated data using Eloquent's create method
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'gambar' => $path,
            'author' => $request->author,
            'tahun' => $request->tahun,
        ]);

        // Return success response with the created post data
        return response()->json([
            'status' => 'success',
            'code' => 201, // HTTP 201 Created
            'data' => $post
        ], 201);
    }

    /**
     * Get a single post by ID
     * 
     * This function retrieves a specific post from the database by its ID.
     * It's connected to a GET route, typically something like GET /api/posts/{id}
     * This is also part of the "R" in CRUD (Read single record operation)
     * 
     * @param int $id The ID of the post to retrieve
     * @return \Illuminate\Http\JsonResponse JSON response containing the post or error if not found
     */
    public function show($id)
    {
        // Find the post by ID using Eloquent's find method
        $post = Post::find($id);
        
        // If post doesn't exist, return 404 error response
        if (!$post) {
            return response()->json([
                'status' => 'Failed get by id',
                'code' => 404, // HTTP 404 Not Found
                'data' => null
            ], 404);
        }

        // Return success response with the found post data
        return response()->json([
            'status' => 'Success get by id',
            'code' => 200,
            'data' => $post
        ]);
    }

    /**
     * Update an existing post
     * 
     * This function validates and updates an existing post in the database.
     * It's connected to a PUT/PATCH route, typically something like PUT /api/posts/{id}
     * This is the "U" in CRUD (Update operation)
     * 
     * @param Request $request HTTP request containing updated post data
     * @param int $id The ID of the post to update
     * @return \Illuminate\Http\JsonResponse JSON response with the updated post or error if not found
     */
    public function update(Request $request, $id)
    {
        // Find the post by ID to ensure it exists before updating
        $post = Post::find($id);
        
        // If post doesn't exist, return 404 error response
        if (!$post) {
            return response()->json([
                'status' => 'Failed update by id',
                'code' => 404, // HTTP 404 Not Found
                'data' => null
            ], 404);
        }

        // Validate the incoming request data with 'sometimes' rules
        // 'sometimes' means the field is only validated if it's present in the request
        $validatedData = $request->validate( [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required',
            'gambar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author' => 'sometimes|required|string|max:100',
            'tahun' => 'sometimes|required|integer',
        ]);

        // Update the post with validated data using Eloquent's update method
        $post->update($validatedData);

        // Return success response with the updated post data
        return response()->json([
            'status' => 'Success update post',
            'code' => 200,
            'data' => $post
        ]);
    }

    /**
     * Delete a post
     * 
     * This function removes a post from the database by its ID.
     * It's connected to a DELETE route, typically something like DELETE /api/posts/{id}
     * This is the "D" in CRUD (Delete operation)
     * 
     * @param int $id The ID of the post to delete
     * @return \Illuminate\Http\JsonResponse JSON response confirming deletion or error if not found
     */
    public function destroy($id)
    {
        // Find the post by ID to ensure it exists before deleting
        $post = Post::find($id);
        
        // If post doesn't exist, return 404 error response
        if (!$post) {
            return response()->json([
                'status' => 'Failed get by id',
                'code' => 404, // HTTP 404 Not Found
                'data' => null
            ], 404);
        }

        // Delete the post from the database using Eloquent's delete method
        $post->delete();

        // Return success response confirming deletion
        return response()->json([
            'status' => 'Success delete post',
            'code' => 200,
            'data' => null
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * REGISTER FUNCTION
     * 
     * Purpose: Handles user registration by validating input, creating a new user,
     * and generating an authentication token.
     * 
     * Process Flow:
     * 1. Validates incoming request data (name, email, passwords)
     * 2. Checks if password and password confirmation match
     * 3. Creates a new user record in the database with hashed password
     * 4. Generates a new API token for the user
     * 5. Returns user data and token in JSON response
     * 
     * Database Connections:
     * - Creates a record in the 'users' table
     * - Creates a token in the 'personal_access_tokens' table (via Laravel Sanctum)
     * 
     * Validation Rules:
     * - Name: Required, string, max 100 characters
     * - Email: Required, valid email format, max 150 characters, must be unique in users table
     * - Password: Required, string, minimum 8 characters
     * - Password Confirm: Required, string, minimum 8 characters
     */
    public function register(Request $req){
        // Validate incoming request data against defined rules
        $data = $req->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirm' => ['required', 'string', 'min:8']
        ]);

        // Check if password and confirmation password match
        // If they don't match, return error response with 422 status code
        if($data['password'] != $data['password_confirm']){
            return response()->json([
                "message" => "Confirmasi password salah!",
                "code" => 422
            ]);
        }

        // Create new user in database with hashed password
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Generate API token for the newly created user
        // 'api-token' is the token name, can be customized
        $token = $user->createToken('api-token')->plainTextToken;

        // Return success response with user data and authentication token
        return response()->json([
            'message' => "Register successful",
            'code' => 201,
            'data' => $user,
            'token' => $token
        ]);
    }
}
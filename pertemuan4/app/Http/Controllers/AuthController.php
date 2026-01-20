<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $req){
        $data = $req->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirm' => ['required', 'string', 'min:8']
        ]);

        if($data['password'] != $data['password_confirm']){
            return response()->json([
                "message" => "Confirmasi password salah!",
                "code" => 422
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => "Register successful",
            'code' => 201,
            'data' => $user,
            'token' => $token
        ]);
    }
}
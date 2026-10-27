<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //Register 
    public function register(Request $request) 
    {
        $validated = $request->validate([
            'name' => ["required", "string", "max:255"],
            'email' => ["required", 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8']
        ]);


        $user = User::create([
            ...$validated,
        ]);


        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    //get list all users
    public function index () {
        $user = User::paginate(10);
        return response()->json($user);
    }


}

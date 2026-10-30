<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
     //create admin 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ["required", "string", "max:255"],
            'email' => ["required", 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8']
        ]);


        $admin = User::create([
            ...$validated,
            'role' => 'admin'
        ]);


        $token = $admin->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'admin' => $admin,
            'token' => $token
        ], 201);
    }
}

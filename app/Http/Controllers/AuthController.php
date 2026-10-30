<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

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
            'role' => 'user'
        ]);


        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    //Login 
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8']
        ]);


        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or password incorrect.'
            ], 401);
        }


        //check if account is inactive
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been deactivated. please contact to support.'
            ], 403);
        }


        $token = $user->createToken('api-token')->plainTextToken;


        return response()->json([
            'success' => true,
            'message' => 'Login successfully.',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    //get list all users
    public function index()
    {
        $user = User::paginate(10);
        return response()->json($user);
    }

    //me
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ], 200);
    }


    //logout account
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }


    //admin can delete that user
    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $user->is_active = false;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ], 200);
    }
}

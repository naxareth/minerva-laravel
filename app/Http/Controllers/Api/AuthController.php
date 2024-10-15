<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
{
    $request->validate([
        'email' => 'required|email|max:255',
        'password' => 'required|string|min:8|max:255',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'The provided credentials are incorrect'
        ], 401);
    }

    // The second argument is now an array of abilities
    $token = $user->createToken($user->name, ['*'])->plainTextToken;

    return response()->json([
        'message' => 'Login Successful',
        'token_type' => 'Bearer',
        'token' => $token
    ], 200);
}

public function register(Request $request): JsonResponse
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email', // Ensure email is unique
        'password' => 'required|string|min:8|max:255|confirmed', // Use confirmed rule
    ]);

    // Create user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Hash the password
    ]);

    if ($user) {
        // Generate token
        $token = $user->createToken($user->name, ['*'])->plainTextToken;

        return response()->json([
            'message' => 'Registration Successful',
            'token_type' => 'Bearer',
            'token' => $token
        ], 201);
    }

    return response()->json([
        'message' => 'Something went wrong during registration.',
    ], 500);
}

public function logout(Request $request)
{
    $user = User::where('id',$request->user()->id)->first();
    if ($user)
    {
        $user->tokens()->delete();

        return response()->json([
            'message'=>'Logged out successfully'
        ],200);
    }
    else{
        return response()->json([
            'message'=>'User not found'
        ],404);
    }
}
    public function profile(Request $request)
    {
        if ($request->user())
        {
            return response()->json([
                'message'=>'Profile Fetched',
                'data'=>$request->user()
            ],200);
        }
        else 
        {
            return response()->json([
                'message'=>'Not Authorized.',
               
            ],401);
        }
    }
}
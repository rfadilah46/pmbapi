<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign mahasiswa role
        $user->assignRole('mahasiswa');

        $token = auth()->login($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    /**
     * Login user and create token
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        

        return response()->json([
            'user' => auth()->user(),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
                'roles' => auth()->user()->getRoleNames(),  
            ]
        ]);
    }

    /**
     * Get authenticated user profile
     */
    public function profile(): JsonResponse
    {
        return response()->json([
            'user' => auth()->user(),
            'roles' => auth()->user()->getRoleNames(),
            'formulir' => auth()->user()->formulir
        ]);
    }

    /**
     * Logout user (Invalidate the token)
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::create($validated);

        $token = $user->createToken('access_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'type' => 'Bearer'
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            abort(404, "The given credentials does not match our records");
        }

        return response()->json([
            'access_token' => auth()->user()->createToken('access_token')->plainTextToken
        ]);

    }
}

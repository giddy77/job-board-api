<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use Illuminate\Http\Request;
use Log;

class LoginController extends Controller
{
    public function loginUser(UserLoginRequest $request)
    {
        // return response()->json([
        //     'message' => 'Login functionality not implemented yet.',
        // ], 501); // 501 Not Implemented

        $validated = $request->validated();

        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;


            //Log the successful login attempt for user tracking
            Log::channel('auth')->info('User logged in successfully', [
                'email' => $user->email,
                'logged_in_at' => now(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
                'expiration' => now()->addMinutes((int) config('sanctum.expiration', 10)), // defaults to 10 minutes
            ], 200);

        }
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401); // 401 Unauthorized

    }
}

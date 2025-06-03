<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompanyLoginRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Log;

class LoginController extends Controller
{
    public function loginUser(UserLoginRequest $request)
    {
        $credentials = $request->validated();

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
                'user' => new UserResource($user),
                'token' => $token,
                'expiration' => now()->addMinutes((int) config('sanctum.expiration', 10)), // defaults to 10 minutes
            ], 200);

        }
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401); // 401 Unauthorized

    }

    public function loginCompany(CompanyLoginRequest $request)
    {

        try {
            $credentials = $request->validated();

            if (auth()->guard('company')->attempt($credentials)) {
                $company = auth()->guard('company')->user();
                $token = $company->createToken('auth_token')->plainTextToken;

                //Log the successful login attempt for company tracking
                Log::channel('auth')->info('Company logged in successfully', [
                    'email' => $company->email,
                    'logged_in_at' => now(),
                    'ip_address' => $request->ip(),
                ]);

                return response()->json([
                    'message' => 'Login successful',
                    'company' => new CompanyResource($company),
                    'token' => $token,
                    'expiration' => now()->addMinutes((int) config('sanctum.expiration', 10)), // defaults to 10 minutes
                ], 200);
            }
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401); // 401 Unauthorized

        } catch (\Throwable $e) {
            Log::error('Login error: '.$e->getMessage());
            return response()->json([
                'message' => 'An error occurred during login',
            ], 500); // 500 Internal Server Error
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompanyRegisterRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegisterController extends Controller
{
    //user registration logic
    public function registerUser(UserRegisterRequest $request)
    {
        // Validate the request
        $validated = $request->validated();

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
           //Log the successful registration attempt for user tracking
           Log::channel('auth')->info('User registered successfully', [
            'email' => $user->email,
            'registered_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        // Return a response
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
           'expiration' => now()->addMinutes((int) config('sanctum.expiration', 10)), //defaults to 10 minutes
        ], 201);
    }

    //company registration
    public function registerCompany(CompanyRegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $email_exists = Company::where('email', $validated['email'])->exists();
            if ($email_exists) {
                return response()->json([
                    'message' => 'Company email already exists. Please use a different email or login',
                ], 422); // 422 Unprocessable Entity
            }

            $company = Company::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'company_website' => $validated['company_website'] ?? null,
                'company_description' => $validated['company_description'] ?? null,
            ]);

            Log::channel('auth')->info('Company registered successfully', [
                'email' => $validated['email'],
                'registered_at' => now(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Company registration successful.',
                'company' => [
                    'name' => $company->name,
                    'email' => $company->email,
                    'company_website' => $company->company_website,
                    'company_description' => $company->company_description,
                ],
            ], 201);

        } catch (Throwable $e) {

            Log::error('Unexpected error during company registration', [
                'message' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompanyRegisterRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegisterController extends Controller
{
    //user registration logic

         /**
     * @OA\Post(
     *     path="/api/v1/auth/user/register",
     *     summary="User endpoint register",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", minLength=4, example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered In Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User Registration successful"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@gmail.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-03T15:25:20.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-03T15:25:20.000000Z")
     *             ),
     *             @OA\Property(property="token", type="string", example="17|jb-apijXzKaqj41CsgjJaceu9DV5xlHqbrlCgyW9n4v7ra61157a98"),
     *             @OA\Property(property="expiration", type="string", format="date-time", example="2025-06-03T16:53:15.894527Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed or other error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}}),
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User credentials not valid",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User credentials not valid")
     *         )
     *     )
     * )
     */
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
            'user' => new UserResource($user),
            'token' => $user->createToken('auth_token')->plainTextToken,
           'expiration' => now()->addMinutes((int) config('sanctum.expiration', 10)), //defaults to 10 minutes
        ], 201);
    }

    //company registration
     //user registration logic

         /**
     * @OA\Post(
     *     path="/api/v1/auth/company/register",
     *     summary="Company endpoint register",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            @OA\Property(property="name", type="string", example="Pesa Kit"),
     *             @OA\Property(property="email", type="string", format="email", example="info@Pesakit.com"),
     *             @OA\Property(property="password", type="string", minLength=4, example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company Registerd Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Company Registration successful"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@gmail.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-03T15:25:20.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-03T15:25:20.000000Z")
     *             ),
     *             @OA\Property(property="token", type="string", example="17|jb-apijXzKaqj41CsgjJaceu9DV5xlHqbrlCgyW9n4v7ra61157a98"),
     *             @OA\Property(property="expiration", type="string", format="date-time", example="2025-06-03T16:53:15.894527Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed or other error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}, "email": {"The  email is required."}, "password": {"The password field is required."}, "email": {"The email field is required."}}),
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User credentials not valid",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Company credentials not valid")
     *         )
     *     )
     * )
     */
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
               'company' => new CompanyResource($company),
                'token' => $company->createToken('auth_token')->plainTextToken,
                'expiration' => now()->addMinutes((int) config('sanctum.expiration', 10)), //defaults to 10 minutes
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

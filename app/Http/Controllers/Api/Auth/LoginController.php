<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompanyLoginRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Log;
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Job Board API",
 *     description="This is the Job Board API documentation.",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 *
 * )
     * @OA\SecurityScheme(
     *     type="http",
     *     securityScheme="bearerAuth",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 */

class LoginController extends Controller
{
       /**
     * @OA\Post(
     *     path="/api/v1/auth/user/login",
     *     summary="User endpoint Login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="user@pesakit.com"),
     *             @OA\Property(property="password", type="string", minLength=4, example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User Logged In Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful"),
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
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}, "password": {"The password field is required."}})
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
      /**
     * @OA\Post(
     *     path="/api/v1/auth/company/login",
     *     summary="Company endpoint Login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="info@pesakit.com"),
     *             @OA\Property(property="password", type="string", minLength=4, example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company Logged In Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(
     *                 property="company",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="name", type="string", example="Pesa Kit"),
     *                 @OA\Property(property="email", type="string", example="info@pesakit.com"),
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
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}, "password": {"The password field is required."}})
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



    public function loginCompany(CompanyLoginRequest $request)
    {

        try {
            $credentials = $request->validated();

            if (auth()->guard('company')->attempt($credentials)) {
                $company = auth()->guard('company')->user();
                 // Delete old tokens (optional - for security)
                $company->tokens()->delete();
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
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during login',
            ], 500); // 500 Internal Server Error
        }
    }
}

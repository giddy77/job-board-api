<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/user/job-applications",
     *     summary="Get all job applications",
     *      security={{"bearerAuth":{}}},
     *     tags={"Applicant"},
     *     @OA\Response(
     *         response=200,
     *         description="Job applications retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Job applications retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Backend Engineer"),
     *                         @OA\Property(property="description", type="string", example="Senior Backend Engineer with Laravel and PHP experience"),
     *                         @OA\Property(property="location", type="string", example="Nairobi"),
     *                         @OA\Property(property="job_type", type="string", example="full-time"),
     *                         @OA\Property(property="salary_min", type="number", example=300000),
     *                         @OA\Property(property="salary_max", type="number", example=400000),
     *                         @OA\Property(
     *                             property="requirements",
     *                             type="array",
     *                             @OA\Items(type="string", example="Laravel")
     *                         ),
     *                         @OA\Property(property="benefits", type="string", nullable=true),
     *                         @OA\Property(property="deadline", type="string", format="date", example="2025-08-01"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-03T19:23:53.000000Z"),
     *                         @OA\Property(
     *                             property="company",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Pesa Kittyy")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=10)
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {
        $jobs = JobApplication::with('applicant')->latest()->paginate(10);

        return response()->json([
            'message' => 'Job applications retrieved successfully',
            'data' => ApplicationResource::collection($jobs),
        ]);
    }

    public function show(Request $request, $id)
    {
        // Logic to handle the show request for a specific application
        // This could involve fetching the application by ID and returning it in a resource format.

        // Example:
        // $application = Application::findOrFail($id);
        // return new ApplicationResource($application);
    }

    public function store(Request $request)
    {
        // Logic to handle the store request for creating a new application
        // This could involve validating the request data and saving a new application.

        // Example:
        // $validatedData = $request->validate([
        //     'job_id' => 'required|exists:jobs,id',
        //     'cover_letter' => 'required|string|max:1000',
        // ]);
        // $application = Application::create(array_merge($validatedData, ['user_id' => auth()->id()]));
        // return new ApplicationResource($application);
    }

    public function destroy(Request $request, $id)
    {
        // Logic to handle the destroy request for deleting an application
        // This could involve finding the application by ID and deleting it.

        // Example:
        // $application = Application::findOrFail($id);
        // $application->delete();
        // return response()->json(['message' => 'Application deleted successfully']);
    }
}

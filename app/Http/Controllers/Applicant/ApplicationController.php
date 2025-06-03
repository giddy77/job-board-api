<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
 * @OA\Post(
 *     path="/api/v1/user/job-applications",
 *     summary="Submit a job application",
 *     tags={"Applicant"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"job_posting_id", "cover_letter"},
 *             @OA\Property(property="job_posting_id", type="integer", example=1),
 *             @OA\Property(property="cover_letter", type="string", example="I am passionate about backend development and believe I am a perfect fit for this role.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Job application submitted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Job application submitted successfully"),
 *             @OA\Property(property="application", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="job_posting_id", type="integer", example=1),
 *                 @OA\Property(property="cover_letter", type="string", example="I am passionate about backend development and believe I am a perfect fit for this role."),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-04T12:45:00.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-04T12:45:00.000000Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Job posting not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Job posting not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(property="errors", type="object", example={
 *                 "job_posting_id": {"The job posting id field is required."},
 *                 "cover_letter": {"The cover letter field is required."}
 *             })
 *         )
 *     )
 * )
 */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
            'cover_letter' => 'required|string',
        ]);

        $jobPosting = JobPosting::find($validated['job_posting_id']);
        if (!$jobPosting) {
            return response()->json([
                'message' => 'Job posting not found'
            ], 404);
        }

        $application = JobApplication::create([
            'user_id' => Auth::id(),
            'job_posting_id' => $validated['job_posting_id'],
            'resume_path' => $validated['cover_letter'],
            'applied_at' => now(),
        ]);

        return response()->json([
            'message' => 'Job application submitted successfully',
            'application' => new ApplicationResource($application),
        ], 201);
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

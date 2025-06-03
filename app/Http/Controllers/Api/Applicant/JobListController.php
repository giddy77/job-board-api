<?php

namespace App\Http\Controllers\Api\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobListResource;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobListController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/user/job-listings",
     *     summary="Get all job postings",
     *      security={{"bearerAuth":{}}},
     *     tags={"Applicant"},
     *     @OA\Response(
     *         response=200,
     *         description="Job postings retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Job postings retrieved successfully"),
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
        $jobs = JobPosting::with('company')->latest()->paginate(10);

        return response()->json([
            'message' => 'Job postings retrieved successfully',
            'data' => JobListResource::collection($jobs),
        ]);
    }

}

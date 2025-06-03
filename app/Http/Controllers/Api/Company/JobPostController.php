<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobPostController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/company/job-postings",
     * security={{"bearerAuth":{}}},
     *     summary="Get paginated job postings for a company",
     *     tags={"Jobs"},
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
     *                         @OA\Property(property="company_id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Backend Engineer"),
     *                         @OA\Property(property="description", type="string", example="Senior Backend Engineer with 5+ years experience in Laravel, PHP, and API development"),
     *                         @OA\Property(property="location", type="string", example="Nairobi"),
     *                         @OA\Property(property="salary_min", type="string", example="300000.00"),
     *                         @OA\Property(property="salary_max", type="string", example="400000.00"),
     *                         @OA\Property(property="job_type", type="string", example="full-time"),
     *                         @OA\Property(
     *                             property="requirements",
     *                             type="array",
     *                             @OA\Items(type="string"),
     *                             example={"Laravel", "PHP", "MySQL", "API Development"}
     *                         ),
     *                         @OA\Property(property="benefits", type="string", nullable=true, example=null),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-03T19:23:53.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-03T19:23:53.000000Z"),
     *                         @OA\Property(
     *                             property="company",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Pesa Kittyy")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/v1/company/job-postings?page=1"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/v1/company/job-postings?page=1"),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="url", type="string", nullable=true, example=null),
     *                         @OA\Property(property="label", type="string", example="« Previous"),
     *                         @OA\Property(property="active", type="boolean", example=false)
     *                     )
     *                 ),
     *                 @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="path", type="string", example="http://localhost:8000/api/v1/company/job-postings"),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
     *                 @OA\Property(property="to", type="integer", example=1),
     *                 @OA\Property(property="total", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */

    public function index(Request $request)
    {
        $jobPostings = $request->user()->jobPostings()
            ->with('company:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'message' => 'Job postings retrieved successfully',
            'data' => $jobPostings
        ]);
    }




    /**
     * @OA\Post(
     *     path="/api/v1/company/job-postings",
     *      security={{"bearerAuth":{}}},
     *     summary="Create a new job posting",
     *     tags={"Jobs"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "location", "type", "salary_min", "salary_max", "requirements", "deadline"},
     *             @OA\Property(property="title", type="string", example="Backend Engineer"),
     *             @OA\Property(property="description", type="string", example="Senior Backend Engineer with 5+ years experience in Laravel, PHP, and API development"),
     *             @OA\Property(property="location", type="string", example="Nairobi"),
     *             @OA\Property(property="type", type="string", example="full-time"),
     *             @OA\Property(property="salary_min", type="number", format="float", example=300000),
     *             @OA\Property(property="salary_max", type="number", format="float", example=400000),
     *             @OA\Property(
     *                 property="requirements",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"Laravel", "PHP", "MySQL", "API Development"}
     *             ),
     *             @OA\Property(property="deadline", type="string", format="date", example="2025-08-01")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Job created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Job created successfully"),
     *             @OA\Property(
     *                 property="job",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Backend Engineer"),
     *                 @OA\Property(property="description", type="string", example="Senior Backend Engineer with 5+ years experience in Laravel, PHP, and API development"),
     *                 @OA\Property(property="location", type="string", example="Nairobi"),
     *                 @OA\Property(property="type", type="string", example="full-time"),
     *                 @OA\Property(property="salary_min", type="number", format="float", example=300000),
     *                 @OA\Property(property="salary_max", type="number", format="float", example=400000),
     *                 @OA\Property(
     *                     property="requirements",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"Laravel", "PHP", "MySQL", "API Development"}
     *                 ),
     *                 @OA\Property(property="deadline", type="string", format="date", example="2025-08-01"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-03T20:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-03T20:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}})
     *         )
     *     )
     * )
     */



    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|string|in:full-time,part-time,contract,internship,temporary',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string',
            'deadline' => 'nullable|date|after:today',
        ]);

        $jobPosting = $request->user()->jobPostings()->create($request->all());


        return response()->json([
            'message' => 'Job posting created successfully',
            'data' => $jobPosting->load('company:id,name')
        ], 201);
    }

    public function show(Request $request, JobPosting $jobPosting)
    {
        // Ensure the job posting belongs to the authenticated company
        if ($jobPosting->company_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized access to job posting'
            ], 403);
        }

        return response()->json([
            'message' => 'Job posting retrieved successfully',
            'data' => $jobPosting->load('company:id,name')
        ]);
    }

    /**
 * @OA\Put(
 *     path="/api/v1/company/job-postings/{id}",
 *     security={{"bearerAuth":{}}},
 *     summary="Update an existing job posting",
 *     tags={"Jobs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the job posting",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description", "location", "type", "salary_min", "salary_max", "requirements", "deadline"},
 *             @OA\Property(property="title", type="string", example="Backend Engineer"),
 *             @OA\Property(property="description", type="string", example="Updated description for Backend Engineer"),
 *             @OA\Property(property="location", type="string", example="Nairobi"),
 *             @OA\Property(property="type", type="string", example="full-time"),
 *             @OA\Property(property="salary_min", type="number", format="float", example=350000),
 *             @OA\Property(property="salary_max", type="number", format="float", example=450000),
 *             @OA\Property(
 *                 property="requirements",
 *                 type="array",
 *                 @OA\Items(type="string"),
 *                 example={"Laravel", "PHP", "PostgreSQL", "REST APIs"}
 *             ),
 *             @OA\Property(property="deadline", type="string", format="date", example="2025-09-01")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Job updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Job updated successfully"),
 *             @OA\Property(property="job", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Backend Engineer"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-04T12:00:00.000000Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Job not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Job not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}})
 *         )
 *     )
 * )
 */

    public function update(Request $request, JobPosting $jobPosting)
    {
        // Ensure the job posting belongs to the authenticated company
        if ($jobPosting->company_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized access to job posting'
            ], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|in:full-time,part-time,contract,internship,temporary',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string',
            'is_active' => 'sometimes|boolean',
            'deadline' => 'nullable|date|after:today',
        ]);

        $jobPosting->update($request->all());

        return response()->json([
            'message' => 'Job posting updated successfully',
            'data' => $jobPosting->load('company:id,name')
        ]);
    }

    /**
 * @OA\Delete(
 *     path="/api/v1/company/job-postings/{id}",
 *     security={{"bearerAuth":{}}},
 *     summary="Delete a job posting",
 *     tags={"Jobs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the job posting",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Job deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Job deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Job not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Job not found")
 *         )
 *     )
 * )
 */

    public function destroy(Request $request, JobPosting $jobPosting)
    {
        // Ensure the job posting belongs to the authenticated company
        if ($jobPosting->company_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized access to job posting'
            ], 403);
        }

        $jobPosting->delete();

        return response()->json([
            'message' => 'Job posting deleted successfully'
        ]);
    }

    public function toggle(Request $request, JobPosting $jobPosting)
    {
        // Ensure the job posting belongs to the authenticated company
        if ($jobPosting->company_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized access to job posting'
            ], 403);
        }

        $jobPosting->update(['is_active' => !$jobPosting->is_active]);

        return response()->json([
            'message' => 'Job posting status updated successfully',
            'data' => $jobPosting->load('company:id,name')
        ]);
    }
}

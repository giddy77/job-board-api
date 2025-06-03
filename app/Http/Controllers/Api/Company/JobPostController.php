<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobPostController extends Controller
{
    // SOLUTION 1: Remove the constructor and handle auth in routes
    // Remove the __construct method entirely and add middleware to routes

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

    public function store(Request $request)
    {
        Log::info('JobPost store called', [
            'user_id' => $request->user()->id,
            'request_data' => $request->all()
        ]);

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

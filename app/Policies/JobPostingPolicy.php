<?php

namespace App\Policies;

use App\Models\Company;

class JobPostingPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(Company $company)
    {
        // Only allow companies to create job postings
        return $company instanceof Company;
    }
    public function update(Company $company, $jobPosting)
    {
        // Allow companies to update their own job postings
        return $company->id === $jobPosting->company_id;
    }
    public function delete(Company $company, $jobPosting)
    {
        // Allow companies to delete their own job postings
        return $company->id === $jobPosting->company_id;
    }
}

<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobPosting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobPostingSeeder extends Seeder
{
    /**
     * we'll seed some dummy job postings
     */
    public function run(): void
    {
        $jobPostings = [
            ['title' => 'Backend Developer', 'description' => 'Work on Laravel APIs.', 'location' => 'Nairobi', 'salary_min' => 80000, 'salary_max' => 120000, 'job_type' => 'full-time'],
            ['title' => 'Frontend Developer', 'description' => 'Vue.js SPA development.', 'location' => 'Mombasa', 'salary_min' => 70000, 'salary_max' => 100000, 'job_type' => 'contract'],
            ['title' => 'Project Manager', 'description' => 'Agile software management.', 'location' => 'Kisumu', 'salary_min' => 100000, 'salary_max' => 150000, 'job_type' => 'full-time'],
            ['title' => 'Data Analyst', 'description' => 'Analyze company data.', 'location' => 'Nakuru', 'salary_min' => 75000, 'salary_max' => 110000, 'job_type' => 'part-time'],
            ['title' => 'DevOps Engineer', 'description' => 'CI/CD and cloud infra.', 'location' => 'Thika', 'salary_min' => 95000, 'salary_max' => 130000, 'job_type' => 'full-time'],
            ['title' => 'Mobile Developer', 'description' => 'Flutter & Android dev.', 'location' => 'Nyeri', 'salary_min' => 85000, 'salary_max' => 120000, 'job_type' => 'remote'],
            ['title' => 'UI/UX Designer', 'description' => 'Design clean interfaces.', 'location' => 'Machakos', 'salary_min' => 60000, 'salary_max' => 90000, 'job_type' => 'part-time'],
            ['title' => 'QA Engineer', 'description' => 'Automated and manual tests.', 'location' => 'Nairobi', 'salary_min' => 65000, 'salary_max' => 95000, 'job_type' => 'contract'],
            ['title' => 'Network Admin', 'description' => 'Maintain IT infra.', 'location' => 'Eldoret', 'salary_min' => 80000, 'salary_max' => 110000, 'job_type' => 'full-time'],
            ['title' => 'Tech Support', 'description' => 'Support client apps.', 'location' => 'Nairobi', 'salary_min' => 50000, 'salary_max' => 80000, 'job_type' => 'full-time'],
        ];

        $companyIds = Company::pluck('id')->toArray();

        foreach ($jobPostings as $index => $post) {
            JobPosting::create(array_merge($post, [
                'company_id' => $companyIds[$index % count($companyIds)],
            ]));
        }
    }

}

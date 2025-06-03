<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        $jobIds = JobPosting::pluck('id')->toArray();

        $applications = [
            [0, 0], [0, 1],
            [1, 2], [1, 3],
            [2, 4], [2, 5],
            [3, 6], [3, 7],
            [4, 8], [4, 9],
        ];

        foreach ($applications as [$userIndex, $jobIndex]) {
            JobApplication::create([
                'user_id' => $userIds[$userIndex],
                'job_posting_id' => $jobIds[$jobIndex],
                'applied_at' => now(),
            ]);
        }
    }

}

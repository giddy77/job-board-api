<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_posting_id',
        'user_id',
        'resume',
        'resume_path',
        'status',
        'applied_at'
    ];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function applicant()
    {
        return $this->belongsTo(User::class);
    }
}

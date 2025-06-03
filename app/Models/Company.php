<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Company extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'password',
        'description',
        'company_website',
    ];

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class);
    }
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }


}

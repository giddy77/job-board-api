<?php

use App\Http\Controllers\Api\Company\JobPostController;
use App\Http\Controllers\Api\Applicant\ApplicationController;
use App\Http\Controllers\Api\Applicant\JobListController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;

Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('register', [RegisterController::class, 'registerUser']);
        Route::post('login', [LoginController::class, 'loginUser']);
    });

    Route::prefix('company')->group(function () {
        Route::post('register', [RegisterController::class, 'registerCompany']);
        Route::post('login', [LoginController::class, 'loginCompany']);
    });

});

//protected routes

Route::middleware(['auth:sanctum'])->middleware('throttle:10,1')->group(function() {

    //company job postings
    Route::prefix('company')->group(function () {
        Route::apiResource('job-postings', JobPostController::class);
        // Route::post('job-postings/{jobPosting}/toggle', [JobPostController::class, 'toggle']);


    });

    Route::prefix('user')->group(function () {
        Route::apiResource('job-listings', JobListController::class);
        Route::apiResource('job-applications', ApplicationController::class);
    });

});


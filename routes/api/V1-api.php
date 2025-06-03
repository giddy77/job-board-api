<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;

Route::prefix('user')->group(function () {
    Route::post('register', [RegisterController::class, 'registerUser']);
    Route::post('login', [LoginController::class, 'loginUser']);
});

Route::prefix('company')->group(function () {
    Route::post('register', [RegisterController::class, 'registerCompany']);
    Route::post('login', [LoginController::class, 'loginCompany']);
});

Route::middleware('auth:sanctum')->post('logout', [LoginController::class, 'logout']);

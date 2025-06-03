<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// require_once __DIR__.'/api/v1.php'; //version 1 API routes
Route::middleware('api')
    ->prefix('v1')
    ->group(base_path('routes/Api/V1-api.php'));

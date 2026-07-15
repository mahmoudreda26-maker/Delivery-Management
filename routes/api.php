<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class,  'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class,  'logout']);

        Route::get('/me', [AuthController::class,  'me']);
    });
});
Route::apiResource('drivers', DriverController::class)->only(['index', 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('drivers', DriverController::class)->except(['index', 'show']);
});

// Route::apiResource('drivers', DriverController::class);

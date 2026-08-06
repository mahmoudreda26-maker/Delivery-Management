<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\LoginHistoryController;
use App\Models\FailedLoginAttempt;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'status' => 'Laravel Working'
    ]);
});

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});


Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('drivers', DriverController::class);


    Route::middleware('role:driver')->group(function () {

        Route::get('vehicles/live', [VehicleController::class, 'live']);
        Route::apiResource('vehicles', VehicleController::class);
        Route::patch('vehicles/{id}/assign', [VehicleController::class, 'assignDriver']);
    });
    Route::post('/locations', [LocationController::class, 'store']);
    Route::get('login-history', [LoginHistoryController::class, 'index']);
    Route::post('login-history', [LoginHistoryController::class, 'store']);
    Route::get('login-history/last', [LoginHistoryController::class, 'show']);
    Route::put('login-history/logout', [LoginHistoryController::class, 'update']);
    Route::delete('login-history/old', [LoginHistoryController::class, 'destroy']);

    Route::get('failed-attempt', [FailedLoginAttempt::class, 'index']);
    Route::delete('failed-attempt/old', [FailedLoginAttempt::class, 'destroy']);
});


Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
    Route::post('/locations', [LocationController::class, 'store']);
    Route::get('/locations/history', [LocationController::class, 'history']);
});

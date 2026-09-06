<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\LoginHistoryController;
use App\Http\Controllers\Api\TrackingSessionController;
use App\Http\Controllers\Api\VehicleController;
use App\Models\FailedLoginAttempt;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/activity-logs', [ActivityLogController::class, 'index']);
        Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show']);
    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('drivers', DriverController::class);

    Route::get('vehicles/live', [VehicleController::class, 'live']);

    Route::apiResource('vehicles', VehicleController::class);

    Route::patch('vehicles/{id}/assign',[VehicleController::class, 'assignDriver']);

    Route::get('login-history', [LoginHistoryController::class, 'index']);
    Route::post('login-history', [LoginHistoryController::class, 'store']);
    Route::get('login-history/last', [LoginHistoryController::class, 'show']);
    Route::put('login-history/logout', [LoginHistoryController::class, 'update']);
    Route::delete('login-history/old', [LoginHistoryController::class, 'destroy']);

    Route::get('failed-attempt', [FailedLoginAttempt::class, 'index']);
    Route::delete('failed-attempt/old', [FailedLoginAttempt::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {

    Route::post('/tracking-sessions/start', [TrackingSessionController::class, 'start']);

    Route::post('/tracking-sessions/end', [TrackingSessionController::class, 'end']);

    Route::get('/tracking-sessions/active', [TrackingSessionController::class, 'active']);

    Route::post('/locations', [LocationController::class, 'store']);

    Route::get('/locations/history', [LocationController::class, 'history']);
});

Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {

    Route::get('/tracking-sessions', [TrackingSessionController::class, 'index']);

    Route::get('/tracking-sessions/{trackingSession}/locations', [TrackingSessionController::class, 'locations']);

    Route::get('/vehicles/latest-locations', [LocationController::class, 'latestLocations']);

    Route::get('/vehicles/{vehicle}/latest-location', [LocationController::class, 'latest']);
});

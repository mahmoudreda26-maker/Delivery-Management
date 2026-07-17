<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login');

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        
        Route::apiResource('drivers', DriverController::class);

        Route::middleware('role:manager')->group(function () {
            Route::get('vehicles/live', [VehicleController::class, 'live']);
            Route::apiResource('vehicles', VehicleController::class);
            Route::patch('vehicles/{id}/assign', [VehicleController::class, 'assignDriver']);
        });

    });
});
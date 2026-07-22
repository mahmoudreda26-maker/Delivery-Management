<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\LocationController;


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

/********************************* Vehicles ***********************************/

Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    
   
    Route::get('vehicles/live', [VehicleController::class, 'live']);
    
    Route::apiResource('vehicles', VehicleController::class);
    Route::patch('vehicles/{id}/assign', [VehicleController::class, 'assignDriver']);
});

/**************************************** Location ******************************/

Route::middleware(['auth:sanctum', 'role:driver'])->group(function () {
    Route::post('/locations', [LocationController::class, 'store']);
});
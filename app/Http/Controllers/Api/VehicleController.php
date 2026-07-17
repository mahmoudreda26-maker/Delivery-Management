<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Services\VehicleService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    use ApiResponse; 

    protected VehicleService $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

   
    public function index(): JsonResponse
    {
        $vehicles = $this->vehicleService->getAllVehicles();
        
       
        return $this->success(
            VehicleResource::collection($vehicles), 
            'Vehicles retrieved successfully.'
        );
    }

   
    public function store(VehicleRequest $request): JsonResponse
    {
     
        $vehicle = $this->vehicleService->createVehicle($request->validated());
        
        return $this->success(
            new VehicleResource($vehicle), 
            'Vehicle created successfully.', 
            201
        );
    }

   
    public function show(string $id): JsonResponse
    {
        $vehicle = $this->vehicleService->getVehicleById($id);
        
        if (!$vehicle) {
           
            return $this->error('Vehicle not found.', [], 404);
        }
        
        return $this->success(
            new VehicleResource($vehicle), 
            'Vehicle details retrieved.'
        );
    }

   
    public function update(VehicleRequest $request, string $id): JsonResponse
    {
       
        $updated = $this->vehicleService->updateVehicle($id, $request->validated());
        
        if (!$updated) {
            return $this->error('Vehicle not found or update failed.', [], 404);
        }
        
        $vehicle = $this->vehicleService->getVehicleById($id);
        return $this->success(
            new VehicleResource($vehicle), 
            'Vehicle updated successfully.'
        );
    }

   
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->vehicleService->deleteVehicle($id);
        
        if (!$deleted) {
            return $this->error('Vehicle not found.', [], 404);
        }
        
        return $this->success(null, 'Vehicle deleted successfully.');
    }

  
    public function assignDriver(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'driver_id' => ['nullable',  'exists:users,id']
        ]);

        $assigned = $this->vehicleService->assignDriver($id, $request->driver_id);
        
        if (!$assigned) {
           
            return $this->error('Vehicle not found.', [], 404);
        }

        $vehicle = $this->vehicleService->getVehicleById($id);
        
      
        return $this->success(
            new VehicleResource($vehicle), 
            'Driver assigned to vehicle successfully.'
        );
    }

   
    public function live(): JsonResponse
    {
        $liveVehicles = $this->vehicleService->getLiveLocations();
        
        return $this->success(
            VehicleResource::collection($liveVehicles), 
            'Live locations retrieved successfully.'
        );
    }
}
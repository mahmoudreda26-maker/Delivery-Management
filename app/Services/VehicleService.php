<?php

namespace App\Services;

use App\Models\Vehicle;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VehicleService
{
    
    public function getAllVehicles()
    {
        return Vehicle::with('driver')->latest()->get();
    }

  
    public function createVehicle(array $data): Vehicle
    {
        $data['id'] = (string) Str::uuid(); 
        return Vehicle::create($data);
    }

    public function getVehicleById(string $id): ?Vehicle
    {
        return Vehicle::with('driver')->find($id);
    }

   
    public function updateVehicle(string $id, array $data): bool
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return false;
        }
        return $vehicle->update($data);
    }

   
    public function deleteVehicle(string $id): bool
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return false;
        }
        return $vehicle->delete();
    }

   
  public function assignDriver(string $vehicleId, string $driverId)
{
    $vehicle = \App\Models\Vehicle::findOrFail($vehicleId);

    $vehicle->user_id = $driverId; 
    $vehicle->save(); 

   
    return $vehicle->load('driver');
}

    public function getLiveLocations()
    {
       
        return Vehicle::with(['driver', 'latestLocation'])
            ->where('status', 'active') 
            ->get();
    }
}
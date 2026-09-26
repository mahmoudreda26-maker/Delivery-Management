<?php

namespace App\Repositories;

use App\Models\Vehicle;

class VehicleRepository
{
    public function getAllVehicles()
    {
        return Vehicle::with('driver')->latest()->get();
    }
    public function getVehicleById(string $id): ?Vehicle
    {
        return Vehicle::with('driver')->find($id);
    }
    public function createVehicle(array $data): Vehicle
    {
        return Vehicle::create($data);
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
    public function assignDriver(string $vehicleId, string $driverId): ?Vehicle
    {
        $vehicle = Vehicle::find($vehicleId);
        if (!$vehicle) {
            return null;
        }
        $vehicle->user_id = $driverId;
        $vehicle->save();
        return $vehicle->load('driver');
    }
    public function getLiveLocations()
    {
        return Vehicle::with(['driver', 'latestLocation'])->where('status', 'active')->get();
    }
}

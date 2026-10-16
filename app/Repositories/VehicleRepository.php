<?php

namespace App\Repositories;

use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($vehicleId, $driverId) {

            // قفل سجل العربية المطلوبة
            $vehicle = Vehicle::whereKey($vehicleId)
                ->lockForUpdate()
                ->first();

            if (!$vehicle) {
                return null;
            }

            // لو السائق مرتبط بعربية أخرى، نفك الارتباط
            Vehicle::where('user_id', $driverId)
                ->where('id', '!=', $vehicle->id)
                ->update(['user_id' => null]);

            // تعيين السائق الجديد للعربية
            $vehicle->user_id = $driverId;
            $vehicle->save();

            return $vehicle->fresh('user');
        });
    }


    public function getLiveLocations()
    {
        return Vehicle::with(['driver', 'latestLocation'])->where('status', 'active')->get();
    }
}

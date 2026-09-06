<?php

namespace App\Services;

use App\Events\VehicleAssigned;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VehicleService
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {}

    public function getAllVehicles()
    {
        return Vehicle::with('driver')->latest()->get();
    }

    public function createVehicle(array $data): Vehicle
    {
        $data['id'] = (string) Str::uuid();

        $vehicle = Vehicle::create($data);

        $this->activityLogService->log(
            user: Auth::user(),
            subject: $vehicle,
            event: 'created',
            description: 'Vehicle created successfully.',
            request: request()
        );

        return $vehicle;
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

        $updated = $vehicle->update($data);

        if ($updated) {
            $this->activityLogService->log(
                user: Auth::user(),
                subject: $vehicle,
                event: 'updated',
                description: 'Vehicle updated successfully.',
                request: request()
            );
        }

        return $updated;
    }

    public function deleteVehicle(string $id): bool
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return false;
        }

        $this->activityLogService->log(
            user: Auth::user(),
            subject: $vehicle,
            event: 'deleted',
            description: 'Vehicle deleted successfully.',
            request: request()
        );

        return $vehicle->delete();
    }

    public function assignDriver(string $vehicleId, string $driverId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        $vehicle->user_id = $driverId;
        $vehicle->save();

        $vehicle->load('driver');

        event(new VehicleAssigned($vehicle->driver,$vehicle));

        $this->activityLogService->log(
            user: Auth::user(),
            subject: $vehicle,
            event: 'driver_assigned',
            description: 'Driver assigned to vehicle.',
            request: request()
        );

        return $vehicle;
    }

    public function getLiveLocations()
    {
        return Vehicle::with(['driver', 'latestLocation'])
            ->where('status', 'active')
            ->get();
    }
}

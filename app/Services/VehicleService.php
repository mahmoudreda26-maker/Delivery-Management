<?php

namespace App\Services;

use App\Events\VehicleAssigned;
use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VehicleService
{
    public function __construct(protected ActivityLogService $activityLogService, protected VehicleRepository $vehicleRepository) {}
    public function getAllVehicles()
    {
        return $this->vehicleRepository->getAllVehicles();
    }
    public function createVehicle(array $data): Vehicle
    {
        $data['id'] = (string) Str::uuid();
        $vehicle = $this->vehicleRepository->createVehicle($data);
        $this->activityLogService->log(user: Auth::user(), subject: $vehicle, event: 'created', description: 'Vehicle created successfully.', request: request());
        return $vehicle;
    }
    public function getVehicleById(string $id): ?Vehicle
    {
        return $this->vehicleRepository->getVehicleById($id);
    }
    public function updateVehicle(string $id, array $data): bool
    {
        $updated = $this->vehicleRepository->updateVehicle($id, $data);
        if ($updated) {
            $vehicle = $this->vehicleRepository->getVehicleById($id);
            $this->activityLogService->log(user: Auth::user(), subject: $vehicle, event: 'updated', description: 'Vehicle updated successfully.', request: request());
        }
        return $updated;
    }
    public function deleteVehicle(string $id): bool
    {
        $vehicle = $this->vehicleRepository->getVehicleById($id);
        if (!$vehicle) {
            return false;
        }
        $this->activityLogService->log(user: Auth::user(), subject: $vehicle, event: 'deleted', description: 'Vehicle deleted successfully.', request: request());
        return $this->vehicleRepository->deleteVehicle($id);
    }
    public function assignDriver(string $vehicleId, string $driverId)
    {
        $vehicle = $this->vehicleRepository->assignDriver($vehicleId, $driverId);
        if (!$vehicle) {
            return null;
        }
        event(new VehicleAssigned($vehicle->driver, $vehicle));
        $this->activityLogService->log(user: Auth::user(), subject: $vehicle, event: 'driver_assigned', description: 'Driver assigned to vehicle.', request: request());
        return $vehicle;
    }
    public function getLiveLocations()
    {
        return $this->vehicleRepository->getLiveLocations();
    }
}

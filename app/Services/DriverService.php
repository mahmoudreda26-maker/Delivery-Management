<?php

namespace App\Services;

use App\Repositories\DriverRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DriverService
{
    public function __construct(protected ActivityLogService $activityLogService, protected DriverRepository $driverRepository) {}
    public function getDrivers()
    {
        return $this->driverRepository->getDrivers();
    }
    public function getDriver(string $id)
    {
        return $this->driverRepository->getDriver($id);
    }
    public function addDriver(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $driver = $this->driverRepository->addDriver($data);
        $this->activityLogService->log(user: Auth::user(), subject: $driver, event: 'created', description: 'Driver created successfully.', request: request());
        return $driver;
    }
    public function updateDriver(array $data, string $id)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $driver = $this->driverRepository->updateDriver($data, $id);
        $this->activityLogService->log(user: Auth::user(), subject: $driver, event: 'updated', description: 'Driver updated successfully.', request: request());
        return $driver;
    }
    public function deleteDriver(string $id)
    {
        $driver = $this->driverRepository->getDriver($id);
        $this->activityLogService->log(user: Auth::user(), subject: $driver, event: 'deleted', description: 'Driver deleted successfully.', request: request());
        return $this->driverRepository->deleteDriver($id);
    }
}

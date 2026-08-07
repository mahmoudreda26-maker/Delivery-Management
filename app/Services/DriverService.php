<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DriverService
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {}

    public function getDrivers()
    {
        return User::where('role', 'driver')->paginate(10);
    }

    public function getDriver(string $id)
    {
        return User::where('role', 'driver')->findOrFail($id);
    }

    public function addDriver(array $data)
    {
        $driver = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'phone'     => $data['phone'] ?? null,
            'role'      => 'driver',
            'is_active' => $data['is_active'],
        ]);

        $this->activityLogService->log(
            user: Auth::user(),
            subject: $driver,
            event: 'created',
            description: 'Driver created successfully.',
            request: request()
        );

        return $driver;
    }

    public function updateDriver(array $data, string $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $driver->update($data);

        $this->activityLogService->log(
            user: Auth::user(),
            subject: $driver,
            event: 'updated',
            description: 'Driver updated successfully.',
            request: request()
        );

        return $driver->fresh();
    }

    public function deleteDriver(string $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);

        $this->activityLogService->log(
            user: Auth::user(),
            subject: $driver,
            event: 'deleted',
            description: 'Driver deleted successfully.',
            request: request()
        );

        $driver->delete();
    }
}
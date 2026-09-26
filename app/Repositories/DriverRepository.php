<?php

namespace App\Repositories;

use App\Models\User;

class DriverRepository
{
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
        return User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password'], 'phone' => $data['phone'] ?? null, 'role' => 'driver', 'is_active' => $data['is_active'],]);
    }
    public function updateDriver(array $data, string $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driver->update($data);
        return $driver->fresh();
    }
    public function deleteDriver(string $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        return $driver->delete();
    }
}

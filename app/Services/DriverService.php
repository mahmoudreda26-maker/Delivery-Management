<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DriverService
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => 'driver',
            'is_active' => $data['is_active'],
        ]);
    }

    public function updateDriver(array $data, string $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $driver->update($data);
        return $driver->fresh();
    }
    public function deleteDriver(string $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driver->delete();
    }
}

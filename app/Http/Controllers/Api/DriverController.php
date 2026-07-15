<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $drivers = User::where('role', 'driver')->get();
        return $this->success(DriverResource::collection($drivers), 'Drivers fetched successfully', 200);
    }

    public function store(StoreDriverRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => 'driver',
            'is_active' => $data['is_active'],
        ]);
        return $this->success(
            new DriverResource($user),
            'Driver created successfully',
            201
        );
    }
    public function show(string $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        return $this->success(new DriverResource($driver), 'Driver fetched successfully', 200);
    }

    public function update(UpdateDriverRequest  $request, string $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $driver->update($data);
        return $this->success(new DriverResource($driver), 'Driver updated successfully', 200);
    }


    public function destroy(string $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driver->delete();
        return $this->success(null, 'Driver deleted successfully', 200);
    }
}

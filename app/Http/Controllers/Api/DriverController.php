<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\User;
use App\Services\DriverService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    use ApiResponse;
    public function __construct(
        private DriverService $driverService
    ) {}

    public function index()
    {
        $drivers = $this->driverService->getDrivers();
        return $this->success(DriverResource::collection($drivers), 'Drivers fetched successfully', 200);
    }

    public function store(StoreDriverRequest $request)
    {
        $user = $this->driverService->addDriver(
            $request->validated()
        );
        return $this->success(
            new DriverResource($user),
            'Driver created successfully',
            201
        );
    }
    public function show(string $id)
    {
        $driver = $this->driverService->getDriver($id);
        return $this->success(new DriverResource($driver), 'Driver fetched successfully', 200);
    }

    public function update(UpdateDriverRequest  $request, string $id)
    {
        $driver = $this->driverService->updateDriver(
            $request->validated(),
            $id
        );
        return $this->success(new DriverResource($driver), 'Driver updated successfully', 200);
    }


    public function destroy(string $id)
    {
        $this->driverService->deleteDriver($id);
        return $this->success(null, 'Driver deleted successfully', 200);
    }
}

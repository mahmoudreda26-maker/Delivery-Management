<?php

namespace App\Http\Controllers\Api;

use App\Events\LocationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Vehicle;
use App\Services\LocationService;
use App\Traits\ApiResponse;

class LocationController extends Controller
{
    use ApiResponse;

    public function store(LocationRequest $request, LocationService $locationService)
    {
        $data = $locationService->store($request->validated());

        $vehicle = Vehicle::where('user_id', auth('api')->id())->first();

        if ($vehicle) {
            event(new LocationUpdated($vehicle));
        }

        return $this->success(
            new LocationResource($data),
            'Operation successful',
            201
        );
    }
}
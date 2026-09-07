<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationHistoryRequest;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Http\Resources\TrackingResource;
use App\Models\Vehicle;
use App\Services\LocationService;
use App\Services\TrackingService;
use App\Traits\ApiResponse;

class LocationController extends Controller
{
    use ApiResponse;

    public function store(
        LocationRequest $request,
        TrackingService $trackingService
    ) {
        $location = $trackingService->updateAndBroadcastLocation(
            $request->user(),
            $request->validated()
        );

        return $this->success(
            new LocationResource($location),
            'Operation successful',
            201
        );
    }

    public function history(
        LocationHistoryRequest $request,
        LocationService $locationService
    ) {
        $locations = $locationService->history(
            $request->validated()
        );

        return $this->success(
            LocationResource::collection($locations),
            'Location history retrieved successfully.'
        );
    }

    public function latest(
        Vehicle $vehicle,
        LocationService $locationService
    ) {
        $location = $locationService->latest($vehicle);

        if (!$location) {
            return $this->success(
                null,
                'No location found for this vehicle.'
            );
        }

        return $this->success(
            new TrackingResource($location),
            'Latest location retrieved successfully.'
        );
    }

    public function latestLocations(LocationService $locationService)
    {
        $locations = $locationService->latestLocations();

        return $this->success(
           LocationResource::collection($locations),
            'Latest locations retrieved successfully.'
        );
    }
}
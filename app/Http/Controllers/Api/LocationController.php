<?php

namespace App\Http\Controllers\Api;

use App\Events\LocationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationHistoryRequest;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Services\LocationService;
use App\Traits\ApiResponse;

class LocationController extends Controller
{
    use ApiResponse;

    public function store(LocationRequest $request, LocationService $locationService)
    {
        $location = $locationService->store(
            $request->validated()
        );

        event(new LocationUpdated($location));

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
}
<?php

namespace App\Http\Controllers\Api;

use App\Events\LocationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationHistoryRequest;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Models\Vehicle;
use App\Services\LocationService;
use App\Traits\ApiResponse;

class LocationController extends Controller
{
    use ApiResponse;

    public function store(LocationRequest $request, LocationService $locationService)
    {
        $data = $locationService->store($request->validated());

        $location = Location::where('user_id', auth()->id())->first();

        if ($location) {
            event(new LocationUpdated($location));
        }

        return $this->success(
            new LocationResource($data),
            'Operation successful',
            201
        );
    }

    public function history(LocationHistoryRequest $request, LocationService $locationService)
    {
        $locations = $locationService->history(
            $request->validated()
        );

        return $this->success(
            LocationResource::collection($locations),
            'Location history retrieved successfully.'
        );
    }
}
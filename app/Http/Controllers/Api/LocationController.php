<?php

namespace App\Http\Controllers\Api;

use App\Events\LocationUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationHistoryRequest;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Vehicle;
use App\Services\LocationService;
use App\Traits\ApiResponse;

class LocationController extends Controller
{
    use ApiResponse;
<<<<<<< HEAD
    public function store(LocationRequest $request, LocationService $locationService)
    {
        $data =   $locationService->store($request->validated());
=======

    public function store(LocationRequest $request, LocationService $locationService)
    {
        $data = $locationService->store($request->validated());

        $vehicle = Vehicle::where('user_id', auth('api')->id())->first();

        if ($vehicle) {
            event(new LocationUpdated($vehicle));
        }

>>>>>>> c90ed1ac852b92c6c8cf0895b0572806541e36ca
        return $this->success(
            new LocationResource($data),
            'Operation successful',
            201
        );
    }
<<<<<<< HEAD
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
=======
}
>>>>>>> c90ed1ac852b92c6c8cf0895b0572806541e36ca

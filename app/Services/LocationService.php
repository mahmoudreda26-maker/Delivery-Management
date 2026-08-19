<?php

namespace App\Services;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LocationService
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {}

    public function store(array $data)
    {
        $user = Auth::user();

        if ($user->vehicle) {
            throw ValidationException::withMessages([
                'vehicle' => ['The current user does not have a vehicle.'],
            ]);
        }

        $vehicle = $user->vehicle;

        $location = Location::create([
            'user_id'    => $user->id,
            'vehicle_id' => $vehicle->id,
            'latitude'   => $data['latitude'],
            'longitude'  => $data['longitude'],
            'speed'      => $data['speed'],
        ]);

        $vehicle->update([
            'status' => 'active',
        ]);

        $this->activityLogService->log(
            user: $user,
            subject: $location,
            event: 'created',
            description: 'Location created successfully.',
            request: request()
        );

        return $location;
    }

    public function history(array $data)
    {
        return Location::forVehicle($data['vehicle_id'])
            ->forDate($data['date'])
            ->oldest()
            ->get();
    }
}
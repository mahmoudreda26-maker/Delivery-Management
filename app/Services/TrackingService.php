<?php

namespace App\Services;

use App\Events\LocationUpdated;
use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TrackingService
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {}

    public function updateAndBroadcastLocation(User $user, array $data): Location
    {
        $vehicle = Vehicle::where('user_id', $user->id)->first();

        if (!$vehicle) {
            throw ValidationException::withMessages([
                'driver' => ['Driver does not have an assigned vehicle.'],
            ]);
        }

        $location = Location::create([
            'vehicle_id'  => $vehicle->id,
            'driver_id'   => $user->id,
            'latitude'    => $data['latitude'],
            'longitude'   => $data['longitude'],
            'speed'       => $data['speed'] ?? 0,
            'recorded_at' => now(),
        ]);

        $vehicle->update([
            'status' => 'active',
        ]);

        $this->activityLogService->log(
            user: Auth::user(),
            subject: $location,
            event: 'location_updated',
            description: 'Driver location updated successfully.',
            request: request()
        );

        event(new LocationUpdated($vehicle));

        return $location;
    }
}
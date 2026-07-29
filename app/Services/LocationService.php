<?php

namespace App\Services;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LocationService
{
    public function store(array $data)
    {
        $user = Auth::user();

        if ($user->vehicles->isEmpty()) {
            throw ValidationException::withMessages([
                'vehicle' => ['The current user does not have a vehicle.'],
            ]);
        }

        $vehicle = $user->vehicles->first();

        $location = Location::create([
           'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'latitude'   => $data['latitude'],
            'longitude'  => $data['longitude'],
            'speed'      => $data['speed'],
        ]);

        $vehicle->update([
            'status' => 'active',
        ]);

        return $location;
    }
}
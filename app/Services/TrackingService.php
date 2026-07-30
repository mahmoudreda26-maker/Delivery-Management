<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Vehicle;
use App\Events\LocationUpdated;
use App\Models\User;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log; // أضيفي الاستيراد في الأعلى
class TrackingService
{
   
    public function updateAndBroadcastLocation(User $user, array $data): Location
    {



        $vehicle = Vehicle::where('user_id', $user->id)->first();

        if (!$vehicle) {
            throw ValidationException::withMessages([
                'driver' => ['Driver does not have an assigned vehicle.']
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
            'status' => 'active'
        ]);

       
        event(new LocationUpdated($vehicle));

        return $location;
    }
}
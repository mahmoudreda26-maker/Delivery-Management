<?php

namespace App\Services;

use App\Events\TrackingSessionEnded;
use App\Events\TrackingSessionStarted;
use App\Models\TrackingSession;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrackingSessionService
{
    public function start(User $driver, int $vehicleId): TrackingSession
    {
        $trackingSession = DB::transaction(function () use ($driver, $vehicleId) {

            $vehicle = Vehicle::find($vehicleId);

            if (!$vehicle) {
                throw ValidationException::withMessages([
                    'vehicle_id' => ['Vehicle not found.'],
                ]);
            }

            if ($vehicle->user_id !== $driver->id) {
                throw ValidationException::withMessages([
                    'vehicle_id' => [
                        'This vehicle is not assigned to the current driver.'
                    ],
                ]);
            }

            $activeSession = TrackingSession::where('driver_id', $driver->id)
                ->where('status', 'active')
                ->first();

            if ($activeSession) {
                throw ValidationException::withMessages([
                    'session' => [
                        'Driver already has an active tracking session.'
                    ],
                ]);
            }

            return TrackingSession::create([
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'started_at' => now(),
                'status' => 'active',
            ]);
        });

        event(new TrackingSessionStarted($trackingSession));

        return $trackingSession;
    }

    public function end(User $driver): TrackingSession
    {
        $trackingSession = DB::transaction(function () use ($driver) {

            $session = TrackingSession::where('driver_id', $driver->id)
                ->where('status', 'active')
                ->latest('started_at')
                ->first();

            if (!$session) {
                throw ValidationException::withMessages([
                    'session' => [
                        'No active tracking session found.'
                    ],
                ]);
            }

            $session->update([
                'ended_at' => now(),
                'status' => 'completed',
            ]);

            return $session->fresh();
        });
         event(new TrackingSessionEnded($trackingSession));

        return $trackingSession;
    }

    public function active(User $driver): ?TrackingSession
    {
        return TrackingSession::where('driver_id', $driver->id)
            ->where('status', 'active')
            ->latest('started_at')
            ->first();
    }

    public function getAll()
    {
        return TrackingSession::with([
            'driver',
            'vehicle',
        ])
            ->latest('started_at')
            ->get();
    }

    public function getLocations(TrackingSession $session)
    {
        return $session->locations()
            ->orderBy('recorded_at')
            ->orderBy('id')
            ->get();
    }
}

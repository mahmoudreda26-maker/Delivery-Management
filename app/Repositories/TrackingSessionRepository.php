<?php

namespace App\Repositories;

use App\Models\TrackingSession;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TrackingSessionRepository
{
    public function start(User $driver): TrackingSession
    {
        $vehicle = $driver->vehicle;
        return TrackingSession::create(['driver_id' => $driver->id, 'vehicle_id' => $vehicle->id, 'started_at' => now(), 'status' => 'active',]);
    }
    public function end(User $driver): ?TrackingSession
    {
        $session = TrackingSession::where('driver_id', $driver->id)->where('status', 'active')->latest('started_at')->first();
        if (!$session) {
            return null;
        }
        $session->update(['ended_at' => now(), 'status' => 'completed',]);
        return $session->fresh();
    }
    public function active(User $driver): ?TrackingSession
    {
        return TrackingSession::where('driver_id', $driver->id)->where('status', 'active')->latest('started_at')->first();
    }
    public function getAll()
    {
        return TrackingSession::with(['driver', 'vehicle',])->latest('started_at')->get();
    }
    public function getLocations(TrackingSession $session, int $perPage = 20): LengthAwarePaginator
    {
        return $session->locations()->orderBy('recorded_at')->orderBy('id')->paginate($perPage);
    }
}

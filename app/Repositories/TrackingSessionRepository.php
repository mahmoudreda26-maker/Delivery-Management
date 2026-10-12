<?php

namespace App\Repositories;
use Illuminate\Pagination\CursorPaginator;
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

    public function getLocations(
        TrackingSession $session,
        int $limit = 500,
        ?string $from = null,
        ?string $to = null,
        ?string $cursor = null
    ): CursorPaginator {
        return $session->locations()
            ->when(
                $from,
                fn($query) =>
                $query->where('recorded_at', '>=', $from)
            )
            ->when(
                $to,
                fn($query) =>
                $query->where('recorded_at', '<=', $to)
            )
            ->orderBy('recorded_at', 'asc')
            ->orderBy('id', 'asc')
            ->cursorPaginate(
                $limit,
                ['*'],
                'cursor',
                $cursor
            );
    }

    public function findSessionForLocation(int $driverId, string $recordedAt): ?TrackingSession
    {
        return TrackingSession::query()
            ->where('driver_id', $driverId)
            ->where('started_at', '<=', $recordedAt)
            ->where(function ($query) use ($recordedAt) {
                $query->whereNull('ended_at')
                    ->orWhere('ended_at', '>=', $recordedAt);
            })
            ->first();
    }
}

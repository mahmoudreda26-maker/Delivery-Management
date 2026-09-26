<?php

namespace App\Services;

use App\Events\TrackingSessionEnded;
use App\Events\TrackingSessionStarted;
use App\Models\TrackingSession;
use App\Models\User;

use App\Repositories\TrackingSessionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TrackingSessionService
{
    public function __construct(
        protected TrackingSessionRepository $trackingSessionRepository
    ) {}

    public function start(User $driver): TrackingSession
    {
        $trackingSession = DB::transaction(function () use ($driver) {

            $vehicle = $driver->vehicle;

            if (!$vehicle) {
                throw ValidationException::withMessages([
                    'vehicle' => [
                        'No vehicle is assigned to the current driver.'
                    ],
                ]);
            }

            $activeSession = $this->trackingSessionRepository->active($driver);

            if ($activeSession) {
                throw ValidationException::withMessages([
                    'session' => [
                        'Driver already has an active tracking session.'
                    ],
                ]);
            }

            return $this->trackingSessionRepository->start($driver);
        });

        event(new TrackingSessionStarted($trackingSession));

        return $trackingSession;
    }

    public function end(User $driver): TrackingSession
    {
        $trackingSession = DB::transaction(function () use ($driver) {

            $session = $this->trackingSessionRepository->active($driver);

            if (!$session) {
                throw ValidationException::withMessages([
                    'session' => [
                        'No active tracking session found.'
                    ],
                ]);
            }

            return $this->trackingSessionRepository->end($driver);
        });

        event(new TrackingSessionEnded($trackingSession));

        return $trackingSession;
    }

    public function active(User $driver): ?TrackingSession
    {
        return $this->trackingSessionRepository->active($driver);
    }

    public function getAll()
    {
        return $this->trackingSessionRepository->getAll();
    }

    public function getLocations(
        TrackingSession $session,
        int $perPage = 20
    ): LengthAwarePaginator {
        return $this->trackingSessionRepository->getLocations(
            $session,
            $perPage
        );
    }
}


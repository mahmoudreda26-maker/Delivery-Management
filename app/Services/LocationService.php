<?php

namespace App\Services;

use App\Models\Location;
use App\Models\TrackingSession;
use App\Models\Vehicle;
use App\Repositories\LocationRepository;
use App\Repositories\TrackingSessionRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LocationService
{
    public function __construct(
        protected LocationRepository $locationRepository,
        protected TrackingSessionRepository $trackingSessionRepository
    ) {}

    public function history(array $data)
    {
        return $this->locationRepository->history($data);
    }

    public function latest(Vehicle $vehicle): ?Location
    {
        return $this->locationRepository->latest($vehicle);
    }

    public function latestLocations()
    {
        return $this->locationRepository->latestLocations();
    }

      public function syncLocations(array $locations): array
    {
        $successful = [];
        $alreadySynced = [];
        $failed = [];

        $user = Auth::user();

        $vehicle = $user->vehicle;

        if (!$vehicle) {
            throw new \RuntimeException(
                'Driver has no assigned vehicle.'
            );
        }

        foreach ($locations as $location) {
            try {
                $clientUuid = $location['client_uuid'];

                $existingLocation = $this->locationRepository
                    ->findByClientUuid($clientUuid);

                if ($existingLocation) {
                    $alreadySynced[] = $clientUuid;
                    continue;
                }

                $recordedAt = Carbon::parse(
                    $location['recorded_at']
                );

                $session = $this->trackingSessionRepository
                    ->findSessionForLocation(
                        $user->id,
                        $recordedAt
                    );

                if (!$session) {
                    $failed[] = [
                        'client_uuid' => $clientUuid,
                        'reason' => 'No valid tracking session for recorded_at.',
                    ];

                    continue;
                }

                $createdLocation = $this->locationRepository->create([
                    'user_id' => $user->id,
                    'vehicle_id' => $vehicle->id,
                    'tracking_session_id' => $session->id,
                    'client_uuid' => $clientUuid,
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                    'speed' => $location['speed'] ?? null,
                    'accuracy' => $location['accuracy'] ?? null,
                    'heading' => $location['heading'] ?? null,
                    'recorded_at' => $recordedAt,
                    'received_at' => now(),
                ]);

                $successful[] = $createdLocation->client_uuid;

            } catch (\Throwable $e) {
                $failed[] = [
                    'client_uuid' => $location['client_uuid'] ?? null,
                    'reason' => "Unable to sync this location.",
                ];
            }
        }

        return [
            'successful' => $successful,
            'already_synced' => $alreadySynced,
            'failed' => $failed,
        ];
    }
}

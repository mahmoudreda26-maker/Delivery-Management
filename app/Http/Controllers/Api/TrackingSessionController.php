<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrackingSessionRequest;
use App\Http\Resources\TrackingResource;
use App\Http\Resources\TrackingSessionResource;
use App\Models\TrackingSession;
use App\Services\TrackingSessionService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackingSessionController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected TrackingSessionService $trackingSessionService
    ) {}

    public function start(
        TrackingSessionRequest $request
    ): JsonResponse {

        $session = $this->trackingSessionService->start(
            $request->user()
        );

        return $this->success(
            new TrackingSessionResource(
                $session->load([
                    'driver',
                    'vehicle',
                ])
            ),
            'Tracking session started successfully.',
            201
        );
    }

    public function end(Request $request): JsonResponse
    {
        $session = $this->trackingSessionService->end(
            $request->user()
        );

        return $this->success(
            new TrackingSessionResource(
                $session->load([
                    'driver',
                    'vehicle',
                ])
            ),
            'Tracking session ended successfully.'
        );
    }

    public function active(Request $request): JsonResponse
    {
        $session = $this->trackingSessionService->active(
            $request->user()
        );

        if (!$session) {
            return $this->success(
                null,
                'No active tracking session.'
            );
        }

        return $this->success(
            new TrackingSessionResource(
                $session->load([
                    'driver',
                    'vehicle',
                ])
            ),
            'Active tracking session retrieved successfully.'
        );
    }

    public function index(): JsonResponse
    {
        $sessions = $this->trackingSessionService->getAll();

        return $this->success(
            TrackingSessionResource::collection($sessions),
            'Tracking sessions retrieved successfully.'
        );
    }

    public function locations(
        Request $request,
        TrackingSession $trackingSession
    ): JsonResponse {

        $perPage = min(
            max((int) $request->query('per_page', 20), 1),
            50
        );

        $locations = $this->trackingSessionService->getLocations(
            $trackingSession,
            $perPage
        );

        return $this->paginated($locations);
    }
}
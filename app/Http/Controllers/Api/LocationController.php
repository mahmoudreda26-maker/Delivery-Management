<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Services\TrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    use ApiResponse;
   
    public function __construct(protected TrackingService $trackingService) {}

    public function store(StoreLocationRequest $request): JsonResponse
    {
        $user = Auth::user(); 

        $location = $this->trackingService->updateAndBroadcastLocation($user, $request->validated());

     return $this->success(
            $location, 
            'Location updated and broadcasted successfully.', 
            200
        );
    }
}

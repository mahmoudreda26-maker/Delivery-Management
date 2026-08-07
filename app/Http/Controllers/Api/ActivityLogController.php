<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActivityLogController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ActivityLogService $activityLogService
    ) {}

    public function index(): JsonResponse
    {
        $logs = $this->activityLogService->getAll();

        return $this->success(
            $logs,
            'Activity logs retrieved successfully.'
        );
    }

    public function show(ActivityLog $activityLog): JsonResponse
    {
        $log = $this->activityLogService->show($activityLog);

        return $this->success(
            $log,
            'Activity log retrieved successfully.'
        );
    }
}
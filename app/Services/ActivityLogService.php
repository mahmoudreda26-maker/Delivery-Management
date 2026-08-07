<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ActivityLogService
{
    public function log(
        User $user,
        Model $subject,
        string $event,
        ?string $description = null,
        ?Request $request = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id'     => $user->id,
            'subject_type'=> $subject::class,
            'subject_id'  => $subject->getKey(),
            'event'       => $event,
            'description' => $description,
            'ip_address'  => $request?->ip(),
            'user_agent'  => $request?->userAgent(),
        ]);
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return ActivityLog::with(['user', 'subject'])
            ->latest()
            ->paginate($perPage);
    }

    public function show(ActivityLog $activityLog): ActivityLog
    {
        return $activityLog->load(['user', 'subject']);
    }
}
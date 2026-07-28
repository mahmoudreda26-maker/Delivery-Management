<?php

namespace App\Services;

use App\Models\FailedLoginAttempt;

class FailedLoginAttemptService
{
    public function store(string $email, string $ipAddress, string $userAgent, string $reason)
    {
        return FailedLoginAttempt::create([
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'reason' => $reason,
            'attempted_at' => now(),
        ]);
    }

    public function getUserAttempts(string $email)
    {
        return FailedLoginAttempt::where('email', $email)
            ->latest('attempted_at')
            ->paginate(10);
    }

    public function deleteOldAttempts()
    {
        return FailedLoginAttempt::where('attempted_at', '<', now()->subYear())->delete();
    }
}

<?php

namespace App\Services;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;

class LoginHistoryServiec
{

    public function store(User $user, Request $request)
    {
       $history = LoginHistory::create([
            'user_id' => $user->id,
            'login_at' => now(),
            'logout_at' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $history;
    }

    public function updateLogout(User $user)
    {
        $history = $user->login_histories()
            ->whereNull('logout_at')
            ->latest('login_at')
            ->first();

        if ($history) {
            $history->update([
                'logout_at' => now(),
            ]);
        }
    }
    public function getUserHistory(User $user)
    {
        return $user->loginHistories()
            ->latest('login_at')
            ->paginate(15);
    }
    public function getLastLogin(User $user)
    {
        return $user->loginHistories()
            ->latest('login_at')
            ->first();
    }
    public function deleteOldHistory()
    {
        return LoginHistory::where('login_at', '<', now()->subYear())
            ->delete();
    }
}

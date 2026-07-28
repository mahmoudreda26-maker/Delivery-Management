<?php

namespace App\Services;

use App\Models\RefreshToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RefreshTokenService
{


    public function issue(User $user): string
    {
        $plainRefreshToken = Str::random(128);

        RefreshToken::create([
            'user_id' => $user->id,
            'token' => Hash::make($plainRefreshToken),
            'expires_at' => Carbon::now()->addDays(20),
            'revoked_at' => null,
        ]);

        return  $plainRefreshToken;
    }

    // public function rotate(User $user): string
    // {
    //     RefreshToken::where('user_id', $user->id)->whereNull('revoked_at')->update([
    //         'revoked_at' => Carbon::now(),
    //     ]);
    //     return $this->issue($user);
    // }

    public function revoke(User $user): void
    {
        RefreshToken::where('user_id', $user->id)->whereNull('revoked_at')
            ->update([
                'revoked_at' => now(),
            ]);
    }
}

<?php

namespace App\Actions\Auth;

use App\Models\Auth\Otp;
use App\Models\User;

class VerifyOtpAction
{
    public function execute(string $email, string $otp, string $type = 'email_verification'): bool
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            return false;
        }

        $otpRecord = Otp::query()
            ->where('user_id', $user->id)
            ->where('otp', $otp)
            ->byType($type)
            ->unused()
            ->valid()
            ->latest()
            ->first();

        if (! $otpRecord) {
            return false;
        }

        $otpRecord->markAsUsed();

        if ($type === 'email_verification') {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        if ($type === 'password_reset') {
            return true;
        }

        return true;
    }
}

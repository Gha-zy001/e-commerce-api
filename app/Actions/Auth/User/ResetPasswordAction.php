<?php

namespace App\Actions\Auth\User;

use App\Models\Auth\Otp;
use App\Models\User;
use Devgh\ApiErrorHandler\Facades\ApiResponse;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    public function execute(User $user, string $password)
    {
        $verified = Otp::query()
            ->where('user_id', $user->id)
            ->byType('password_reset')
            ->whereNotNull('used_at')
            ->where('used_at', '>', now()->subMinutes(10))
            ->latest()
            ->exists();

        if (! $verified) {
            return ApiResponse::error([], 'Please verify your OTP first.', 400);
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        return ApiResponse::success('Password reset successfully.');
    }
}

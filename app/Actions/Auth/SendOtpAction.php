<?php

namespace App\Actions\Auth;

use App\Models\Auth\Otp;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Devgh\ApiErrorHandler\Facades\ApiResponse;
use Illuminate\Support\Facades\Notification;

class SendOtpAction
{
    public function execute(User $user, string $type = 'email_verification')
    {
        $existing = Otp::query()
            ->where('user_id', $user->id)
            ->byType($type)
            ->unused()
            ->valid()
            ->latest()
            ->first();

        if ($existing) {
            return ApiResponse::success('OTP already sent. Please check your email.');
        }

        $otpCode = (string) random_int(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'otp' => $otpCode,
            'type' => $type,
            'expires_at' => now()->addMinutes(10),
        ]);

        Notification::send($user, new SendOtpNotification($otpCode));

        return ApiResponse::success('OTP sent successfully.');
    }
}

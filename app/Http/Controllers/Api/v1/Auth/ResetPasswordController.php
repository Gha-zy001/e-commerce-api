<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\ResetPasswordRequest;
use App\Models\Auth\Otp;
use App\Models\User;
use Devgh\ApiErrorHandler\Facades\ApiResponse;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

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
            'password' => Hash::make($request->password),
        ]);

        return ApiResponse::success('Password reset successfully.');
    }
}

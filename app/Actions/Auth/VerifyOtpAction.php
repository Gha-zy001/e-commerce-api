<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Cache;
use App\Models\User;

class VerifyOtpAction
{
  public function execute(string $email, string $otp): bool
  {
    $cachedOtp = Cache::get('otp_' . $email);

    if (!$cachedOtp || $cachedOtp != $otp) {
      return false;
    }

    Cache::forget('otp_' . $email);

    $user = User::where('email', $email)->first();
    if ($user && is_null($user->email_verified_at)) {
      $user->email_verified_at = now();
      $user->save();
    }

    return true;
  }
}

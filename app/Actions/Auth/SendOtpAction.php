<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Notifications\SendOtpNotification;
use Illuminate\Support\Facades\Cache;

class SendOtpAction
{
  public function execute(User $user)
  {
    $otp = rand(100000, 999999);
    Cache::put('otp_' . $user->id, $otp, now()->addMinutes(10));
    $user->notify(new SendOtpNotification($otp));
  }
}
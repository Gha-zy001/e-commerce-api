<?php

namespace App\Listeners;

use App\Events\CustomerRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Actions\Auth\SendOtpAction;
use App\Models\User;

class SendOtp
{
  /**
   * Create the event listener.
   */
  protected $sendOtpAction;
  public function __construct(SendOtpAction $sendOtpAction)
  {
    $this->sendOtpAction = $sendOtpAction;
  }

  /**
   * Handle the event.
   */
  public function handle(CustomerRegistered $event): void
  {
    $this->sendOtpAction->execute($event->user);
  }
  // public function sendOtp(Request $request, SendOtpAction $sendOtpAction)
  // {
  //   $request->validate(['email' => 'required|email|exists:users,email']);

  //   $user = User::where('email', $request->email)->first();
  //   $sendOtpAction->execute($user);
  //   return response()->json([
  //     'message' => 'OTP sent successfully to your email.'
  //   ]);
  // }
}

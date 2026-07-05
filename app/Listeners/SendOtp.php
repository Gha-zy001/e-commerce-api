<?php

namespace App\Listeners;

use App\Events\CustomerRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Actions\Auth\SendOtpAction;
use App\Models\User;

class SendOtp implements ShouldQueue
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
        if ($event->user) {
            $this->sendOtpAction->execute($event->user);
        }
    }

}

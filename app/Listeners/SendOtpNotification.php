<?php

namespace App\Listeners;

use App\Actions\Auth\SendOtpAction;
use App\Events\UserRegister;

class SendOtpNotification
{
    public function __construct(
        protected SendOtpAction $sendOtpAction,
    ) {}

    public function handle(UserRegister $event): void
    {
        if ($event->user) {
            $this->sendOtpAction->execute($event->user);
        }
    }
}

<?php

namespace App\Services;

use App\Actions\Auth\SendOtpAction;
use App\Models\User;

class OtpService
{
    public function __construct(
        protected SendOtpAction $sendOtpAction,
    ) {}

    public function generateAndSend(User $user, string $type = 'email_verification'): void
    {
        $this->sendOtpAction->execute($user, $type);
    }
}

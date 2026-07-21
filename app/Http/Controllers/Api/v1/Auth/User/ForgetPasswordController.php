<?php

namespace App\Http\Controllers\Api\v1\Auth\User;

use App\Actions\Auth\User\SendOtpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\ForgetPasswordRequest;
use App\Models\User;

class ForgetPasswordController extends Controller
{
    public function __invoke(ForgetPasswordRequest $request, SendOtpAction $sendOtpAction)
    {
        $user = User::where('email', $request->email)->first();

        return $sendOtpAction->execute($user, 'password_reset');
    }
}

<?php

namespace App\Http\Controllers\Api\v1\Auth\User;

use App\Actions\Auth\User\ResetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\ResetPasswordRequest;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request, ResetPasswordAction $resetPasswordAction)
    {
        $user = User::where('email', $request->email)->first();

        return $resetPasswordAction->execute($user, $request->password);
    }
}

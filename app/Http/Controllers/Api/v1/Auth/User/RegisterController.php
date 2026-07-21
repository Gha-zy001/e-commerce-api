<?php

namespace App\Http\Controllers\Api\v1\Auth\User;

use App\Actions\Auth\User\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, RegisterAction $registerAction)
    {
        return $registerAction->execute($request->validated(), $request->userAgent());
    }
}

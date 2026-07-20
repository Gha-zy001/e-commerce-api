<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Actions\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, RegisterAction $registerAction)
    {
        return $registerAction->execute($request->validated(), $request->userAgent());
    }
}

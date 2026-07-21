<?php

namespace App\Http\Controllers\Api\v1\Auth\User;

use App\Actions\Auth\User\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, LoginAction $loginAction): JsonResponse
    {
        $request->authenticate($request->email);

        return $loginAction->execute($request->email, $request->password, $request->userAgent());
    }
}

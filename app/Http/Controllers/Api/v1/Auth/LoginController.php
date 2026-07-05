<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Actions\Auth\LoginAction;
use Devgh\ApiErrorHandler\Facades\ApiResponse;

class LoginController extends Controller
{
  public function __invoke(LoginRequest $request, LoginAction $action)
  {
    $result = $action->execute($request->email, $request->password);
    return ApiResponse::success($result, 'User logged in successfully.');
  }
}

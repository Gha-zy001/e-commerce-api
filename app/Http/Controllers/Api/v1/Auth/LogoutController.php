<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Auth\LogoutAction;
use Devgh\ApiErrorHandler\Facades\ApiResponse;

class LogoutController extends Controller
{
  public function __invoke(Request $request, LogoutAction $action)
  {
    $action->execute($request->user());
    return ApiResponse::success([], 'User logged out successfully.');
  }
}

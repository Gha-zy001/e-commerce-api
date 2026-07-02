<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware): void {
    //
  })
  ->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (Throwable $e, Request $request) {
      if ($request->expectsJson() || $request->is('api/*')) {
        // 🟢 1. Validation Errors
        if ($e instanceof \Illuminate\Validation\ValidationException) {
          return \App\Support\ApiResponse::error(
            "Validation error",
            $e->errors(),
            422
          );
        }

        // 🟢 2. Model Not Found
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
          return \App\Support\ApiResponse::error(
            "Resource not found",
            [],
            404
          );
        }

        // 🟢 3. Route Not Found
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
          return \App\Support\ApiResponse::error(
            "Route not found.",
            [],
            404
          );
        }

        // 🟢 4. Auth Error (Unauthenticated)
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
          return \App\Support\ApiResponse::error(
            "Unauthenticated",
            [],
            401
          );
        }

        // 🟢 5. Auth Error (Unauthorized)
        if (
          $e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException ||
          $e instanceof \Illuminate\Auth\Access\AuthorizationException
        ) {
          return \App\Support\ApiResponse::error(
            "Unauthorized action",
            [],
            403
          );
        }

        // 🟢 6. HTTP Errors (remaining ones)
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
          return \App\Support\ApiResponse::error(
            $e->getMessage() ?: "HTTP error",
            [],
            $e->getStatusCode()
          );
        }

        // 🟢 7. Default Server Error (production safe)
        return \App\Support\ApiResponse::error(
          app()->environment('production')
          ? "Something went wrong"
          : $e->getMessage(),
          [],
          method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500
        );
      }
    });
  })->create();

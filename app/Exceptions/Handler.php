<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // This handles redirection when user is not authenticated
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        $guard = $exception->guards()[0] ?? null;

        switch ($guard) {
            case 'admin':
                $login = '/admin/login';
                break;
            case 'customer':
                $login = '/login';
                break;
            default:
                $login = '/login';
        }

        return redirect()->guest($login);
    }
}

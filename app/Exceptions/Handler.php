<?php

namespace App\Exceptions;

use App\Traits\MainTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Str;


class Handler extends ExceptionHandler
{
    use MainTrait;

    protected $dontReport = [
        
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                if (empty($request->headers->get('Authorization'))) {
                    return response()->json([
                        'code' => 401,
                        'message' => 'Token Not Found', // Token tidak ada
                        'status' => false,
                        'result' => null
                    ], 401);
                }
                return response()->json([
                    'code' => 401,
                    'message' => 'Not Authenticated', // Token salah
                    'status' => false,
                    'result' => null
                ], 401);
            }
        });

        $this->renderable(function (Throwable $e) {
            if (Str::contains($e->getMessage(), ['could not connect to server:', 'Connection timed out']) && get_class($e) == 'Illuminate\\Database\\QueryException') {
                return $this->sendResponseData(500, 'Connection Time out.', false, null);
            }
            if (Str::contains($e->getMessage(), ['could not connect to server:', 'Connection timed out']) && get_class($e) == 'PDOException') {
                return $this->sendResponseData(500, 'Connection Time out.', false, null);
            }

            return $this->sendResponseData(500, $e->getMessage(), false, null);
        });
    }
}

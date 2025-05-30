<?php

namespace App\Http\Controllers\v1\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\API\Auth\AuthRequest;
use App\Services\v1\API\Auth\AuthService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ){}

    public function login(AuthRequest $request)
    {
        return $this->authService->login($request);
    }

    public function me()
    {
        return $this->authService->me();
    }

    public function logout()
    {
        return $this->authService->logout();
    }

    public function register(AuthRequest $request)
    {
        return $this->authService->register($request);
    }

    public function sendResetLinkEmail(AuthRequest $request)
    {
        return $this->authService->sendResetLinkEmail($request);
    }

    public function resetPassword(AuthRequest $request)
    {
        return $this->authService->resetPassword($request);
    }
}

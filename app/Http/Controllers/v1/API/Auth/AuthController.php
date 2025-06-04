<?php

namespace App\Http\Controllers\v1\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\API\Auth\AuthRequest;
use App\Services\v1\API\Auth\AuthService;

class AuthController extends Controller
{
    // Injecting the AuthService dependency
    public function __construct(
        protected AuthService $authService
    ){}

    /**
     * Handle login request
     * 
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        return $this->authService->login($request);
    }

    /**
     * Get authenticated user info
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->authService->me();
    }

    /**
     * Logout the authenticated user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return $this->authService->logout();
    }

    /**
     * Register a new user
     * 
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AuthRequest $request)
    {
        return $this->authService->register($request);
    }

    /**
     * Send password reset link to email
     * 
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(AuthRequest $request)
    {
        return $this->authService->sendResetLinkEmail($request);
    }

    /**
     * Reset password using token
     * 
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(AuthRequest $request)
    {
        return $this->authService->resetPassword($request);
    }
}

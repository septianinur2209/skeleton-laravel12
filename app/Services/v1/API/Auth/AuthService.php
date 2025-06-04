<?php

namespace App\Services\v1\API\Auth;

use App\Repositories\v1\API\Auth\AuthRepository;
use App\Traits\MainTrait;
use Illuminate\Http\Response;

class AuthService
{
    use MainTrait;

    // Inject the AuthRepository dependency
    public function __construct(
        protected AuthRepository $authRepository
    ) {}

    // Handle user login
    public function login($request) {
        
        list($code, $response) = $this->authRepository->login($request);

        if ($code == Response::HTTP_OK) {

            // Return successful login response with data
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error response if login fails
        return $this->sendError($response['message'], $code);

    }

    // Get authenticated user details
    public function me() {
        
        list($code, $response) = $this->authRepository->me();

        if ($code == Response::HTTP_OK) {

            // Return user details
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if fetching user fails
        return $this->sendError($response['message'], $code);

    }

    // Handle user logout
    public function logout() {
        
        list($code, $response) = $this->authRepository->logout();

        if ($code == Response::HTTP_OK) {

            // Return successful logout response
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if logout fails
        return $this->sendError($response['message'], $code);

    }

    // Handle user registration
    public function register($request) {
        
        list($code, $response) = $this->authRepository->register($request);

        if ($code == Response::HTTP_OK) {

            // Return success response after registration
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if registration fails
        return $this->sendError($response['message'], $code);

    }

    // Send password reset link to email
    public function sendResetLinkEmail($request) {
        
        list($code, $response) = $this->authRepository->sendResetLinkEmail($request);

        if ($code == Response::HTTP_OK) {

            // Return success response when email is sent
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if sending email fails
        return $this->sendError($response['message'], $code);

    }

    // Handle password reset process
    public function resetPassword($request) {
        
        list($code, $response) = $this->authRepository->resetPassword($request);

        if ($code == Response::HTTP_OK) {

            // Return success response when password is reset
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if reset process fails
        return $this->sendError($response['message'], $code);

    }

}

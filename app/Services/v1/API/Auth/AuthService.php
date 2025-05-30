<?php

namespace App\Services\v1\API\Auth;

use App\Repositories\v1\API\Auth\AuthRepository;
use App\Traits\MainTrait;
use Illuminate\Http\Response;

class AuthService
{
    use MainTrait;

    public function __construct(
        protected AuthRepository $authRepository
    ) {}

    public function login($request) {
        
        list($code, $response) = $this->authRepository->login($request);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function me() {
        
        list($code, $response) = $this->authRepository->me();

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function logout() {
        
        list($code, $response) = $this->authRepository->logout();

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function register($request) {
        
        list($code, $response) = $this->authRepository->register($request);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function sendResetLinkEmail($request) {
        
        list($code, $response) = $this->authRepository->sendResetLinkEmail($request);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function resetPassword($request) {
        
        list($code, $response) = $this->authRepository->resetPassword($request);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

}

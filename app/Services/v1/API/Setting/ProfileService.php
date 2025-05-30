<?php

namespace App\Services\v1\API\Setting;

use App\Repositories\v1\API\Setting\ProfileRepository;
use App\Traits\MainTrait;
use Illuminate\Http\Response;

class ProfileService
{
    use MainTrait;

    public function __construct(
        protected ProfileRepository $settingRepository
    ) {}

    public function me() {
        
        list($code, $response) = $this->settingRepository->me();

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function update($request) {
        
        list($code, $response) = $this->settingRepository->update($request);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function updateProfilePhoto($request) {
        
        list($code, $response) = $this->settingRepository->updateProfilePhoto($request);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function updatePassword($request) {
        
        list($code, $response) = $this->settingRepository->updatePassword($request);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

}

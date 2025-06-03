<?php

namespace App\Services\v1\API\Setting;

use App\Repositories\v1\API\Setting\ProfileRepository;
use App\Traits\MainTrait;
use Illuminate\Http\Response;

class ProfileService
{
    use MainTrait;

    // Inject the ProfileRepository dependency
    public function __construct(
        protected ProfileRepository $settingRepository
    ) {}

    // Get the profile data of the authenticated user
    public function me() {
        
        list($code, $response) = $this->settingRepository->me();

        if ($code == Response::HTTP_OK) {

            // Return successful profile data response
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if fetching profile data fails
        return $this->sendError($response['message'], $code);

    }

    // Update basic profile information
    public function update($request) {
        
        list($code, $response) = $this->settingRepository->update($request);

        if ($code == Response::HTTP_OK) {

            // Return success response after update
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if update fails
        return $this->sendError($response['message'], $code);

    }

    // Update the user's profile photo
    public function updateProfilePhoto($request) {
        
        list($code, $response) = $this->settingRepository->updateProfilePhoto($request);

        if ($code == Response::HTTP_OK) {

            // Return success response with updated photo info
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if updating photo fails
        return $this->sendError($response['message'], $code);

    }

    // Change the user's password
    public function updatePassword($request) {
        
        list($code, $response) = $this->settingRepository->updatePassword($request);

        if ($code == Response::HTTP_OK) {

            // Return success response after password change
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if password update fails
        return $this->sendError($response['message'], $code);

    }

}

<?php

namespace App\Services\v1\API\Setting;

use App\Repositories\v1\API\Setting\UserRepository;
use App\Traits\MainTrait;
use Illuminate\Http\Response;

class UserService
{
    use MainTrait;

    // Inject the UserRepository dependency
    public function __construct(
        protected UserRepository $settingRepository
    ) {}

    // Retrieve a list of users based on request filters
    public function show($request) {
        
        list($code, $response) = $this->settingRepository->show($request);

        if ($code == Response::HTTP_OK) {

            // Return filtered user list if successful
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if retrieval fails
        return $this->sendError($response['message'], $code);

    }

    // Retrieve a single user by their ID
    public function showId($id) {
        
        list($code, $response) = $this->settingRepository->showId($id);

        if ($code == Response::HTTP_OK) {

            // Return user data if found
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if user not found
        return $this->sendError($response['message'], $code);

    }

    // Create a new user
    public function insert($request) {
        
        list($code, $response) = $this->settingRepository->insert($request);

        if ($code == Response::HTTP_OK) {

            // Return response after successful creation
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if creation fails
        return $this->sendError($response['message'], $code);

    }

    // Update user details
    public function update($request, $id) {
        
        list($code, $response) = $this->settingRepository->update($request, $id);

        if ($code == Response::HTTP_OK) {

            // Return response after successful update
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if update fails
        return $this->sendError($response['message'], $code);

    }

    // Update user's active/inactive status
    public function updateStatus($request, $id) {
        
        list($code, $response) = $this->settingRepository->updateStatus($request, $id);

        if ($code == Response::HTTP_OK) {

            // Return response after status change
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if status update fails
        return $this->sendError($response['message'], $code);

    }

    // Delete a user by their ID
    public function delete($id) {
        
        list($code, $response) = $this->settingRepository->delete($id);

        if ($code == Response::HTTP_OK) {

            // Return success after deletion
            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        // Return error if deletion fails
        return $this->sendError($response['message'], $code);

    }

    // Export user data (e.g., to Excel)
    public function download($request)
    {
        return $this->settingRepository->download($request);
    }

}

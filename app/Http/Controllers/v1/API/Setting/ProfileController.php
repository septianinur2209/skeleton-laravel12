<?php

namespace App\Http\Controllers\v1\API\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\API\Setting\ProfileRequest;
use App\Services\v1\API\Setting\ProfileService;

class ProfileController extends Controller
{
    // Inject the ProfileService dependency
    public function __construct(
        protected ProfileService $settingService
    ){}

    /**
     * Get the authenticated user's profile data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->settingService->me();
    }

    /**
     * Update the authenticated user's profile information (e.g., name, email)
     *
     * @param ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileRequest $request)
    {
        return $this->settingService->update($request);
    }

    /**
     * Update the authenticated user's profile photo
     *
     * @param ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfilePhoto(ProfileRequest $request)
    {
        return $this->settingService->updateProfilePhoto($request);
    }

    /**
     * Change the authenticated user's password
     *
     * @param ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(ProfileRequest $request)
    {
        return $this->settingService->updatePassword($request);
    }
}

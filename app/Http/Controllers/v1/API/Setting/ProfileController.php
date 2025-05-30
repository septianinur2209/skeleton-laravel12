<?php

namespace App\Http\Controllers\v1\API\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\API\Setting\ProfileRequest;
use App\Services\v1\API\Setting\ProfileService;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $settingService
    ){}

    public function me()
    {
        return $this->settingService->me();
    }

    public function update(ProfileRequest $request)
    {
        return $this->settingService->update($request);
    }

    public function updateProfilePhoto(ProfileRequest $request)
    {
        return $this->settingService->updateProfilePhoto($request);
    }

    public function updatePassword(ProfileRequest $request)
    {
        return $this->settingService->updatePassword($request);
    }
}

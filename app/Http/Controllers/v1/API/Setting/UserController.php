<?php

namespace App\Http\Controllers\v1\API\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\API\Setting\UserRequest;
use App\Services\v1\API\Setting\UserService;

class UserController extends Controller
{
    // Inject the UserService dependency
    public function __construct(
        protected UserService $settingService
    ){}

    /**
     * Display a filtered list of users (with support for search, pagination, or dropdown)
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(UserRequest $request)
    {
        return $this->settingService->show($request);
    }

    /**
     * Display a single user by ID
     *
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showId($id)
    {
        return $this->settingService->showId($id);
    }

    /**
     * Create a new user
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(UserRequest $request)
    {
        return $this->settingService->insert($request);
    }

    /**
     * Update user data by ID
     *
     * @param UserRequest $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        return $this->settingService->update($request, $id);
    }

    /**
     * Update user status by ID (active/inactive)
     *
     * @param UserRequest $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(UserRequest $request, $id)
    {
        return $this->settingService->updateStatus($request, $id); // If you have a separate `updateStatus` in service, change this.
    }

    /**
     * Delete a user by ID
     *
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        return $this->settingService->delete($id);
    }

    /**
     * Download user data as an Excel file
     *
     * @param UserRequest $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(UserRequest $request)
    {
        return $this->settingService->download($request);
    }
}

<?php

namespace App\Http\Controllers\v1\API\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\API\Setting\UserRequest;
use App\Services\v1\API\Setting\UserService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $settingService
    ){}

    public function show(UserRequest $request)
    {
        return $this->settingService->show($request);
    }

    public function showId($id)
    {
        return $this->settingService->showId($id);
    }

    public function insert(UserRequest $request)
    {
        return $this->settingService->insert($request);
    }

    public function update(UserRequest $request, $id)
    {
        return $this->settingService->update($request, $id);
    }

    public function updateStatus(UserRequest $request, $id)
    {
        return $this->settingService->update($request, $id);
    }

    public function delete($id)
    {
        return $this->settingService->delete($id);
    }

    public function download(UserRequest $request)
    {
        return $this->settingService->download($request);
    }
}

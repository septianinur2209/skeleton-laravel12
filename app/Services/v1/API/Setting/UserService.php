<?php

namespace App\Services\v1\API\Setting;

use App\Repositories\v1\API\Setting\UserRepository;
use App\Traits\MainTrait;
use Illuminate\Http\Response;

class UserService
{
    use MainTrait;

    public function __construct(
        protected UserRepository $settingRepository
    ) {}

    public function show($request) {
        
        list($code, $response) = $this->settingRepository->show($request);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function showId($id) {
        
        list($code, $response) = $this->settingRepository->showId($id);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function insert($request) {
        
        list($code, $response) = $this->settingRepository->insert($request);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function update($request, $id) {
        
        list($code, $response) = $this->settingRepository->update($request, $id);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function updateStatus($request, $id) {
        
        list($code, $response) = $this->settingRepository->updateStatus($request, $id);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function delete($id) {
        
        list($code, $response) = $this->settingRepository->delete($id);

        if ($code == Response::HTTP_OK) {

            return $this->sendResponse($response['data'] ?? null, $response['message']);

        }

        return $this->sendError($response['message'], $code);

    }

    public function download($request)
    {
        return $this->settingRepository->download($request);
    }

}

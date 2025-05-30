<?php

use App\Http\Controllers\v1\API\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api','auth'])->group(function () {

    Route::prefix('v1')->group(function () {

        Route::controller(AuthController::class)->prefix('auth')->group(function () {

            Route::post('login', 'login')->name('login')->withoutMiddleware(['api','auth']);
            Route::post('logout', 'logout')->name('logout');
            Route::post('me', 'me')->name('me');
            
            Route::post('register', 'register')->name('register')->withoutMiddleware(['api','auth']);

            // Route::post('send-reset-link-email', 'sendResetLinkEmail')->name('send-reset-link-email')->withoutMiddleware(['api','auth']);
            // Route::post('reset-password', 'resetPassword')->name('reset-password')->withoutMiddleware(['api','auth']);
        });
    });
});
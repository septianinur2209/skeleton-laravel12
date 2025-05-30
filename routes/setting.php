<?php

use App\Http\Controllers\v1\API\Setting\ProfileController;
use App\Http\Controllers\v1\API\Setting\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth', 'user-permission'])->group(function () {

    Route::prefix('v1/setting')->name('setting.')->group(function () {

        Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->withoutMiddleware('user-permission')->group(function () {

            Route::post('me', 'me')->name('show');
            Route::put('update', 'update')->name('edit');
            Route::put('update-profile-photo', 'updateProfilePhoto')->name('update-profile-photo');
            Route::put('update-password', 'updatePassword')->name('update-password');

        });

        Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {

            // default crud
            Route::post('show', 'show')->name('show');
            Route::post('insert', 'insert')->name('create');
            Route::get('show-id/{id}', 'showId')->name('show-id');
            Route::put('update/{id}', 'update')->name('edit');
            Route::put('update-status/{id}', 'updateStatus')->name('update-status');
            Route::delete('delete/{id}', 'delete')->name('delete');
            
            // excel download
            Route::post('download', 'download')->name('download');

        });

    });
});
<?php

use Illuminate\Support\Facades\Route;
use App\Services\Auth\API\AuthService;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RefreshTokenController;
use App\Http\Controllers\API\Auth\PasswordResetController;
use App\Http\Controllers\API\Auth\UpdatePasswordController;
use App\Http\Controllers\API\Auth\PasswordResetCheckTokenController;

Route::group(
    [
        'prefix' => '/auth',
        'as'     => 'auth.',
    ],
    function () {
        Route::post('/login', LoginController::class)->name('login');
        Route::post('/reset-password', PasswordResetController::class)->name('reset-password');
        Route::get('/reset-password/check-token/{token}', PasswordResetCheckTokenController::class)->name('check-token');
        Route::post('/reset-password/password/update/{token}', UpdatePasswordController::class)->name('updatePassword');

        Route::group(
            [
                'middleware' => AuthService::AUTH_MIDDLEWARE_NAME,
            ],
            function () {
                Route::post('/logout', LogoutController::class)->name('logout');
                Route::post('/refresh-token', RefreshTokenController::class)->name('refresh-token');
            }
        );
    }
);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\Auth\API\AuthService;
use App\Http\Controllers\API\Measure\MediaController;
use App\Http\Controllers\API\Point\GetPointsByIdController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')
    ->group(
        function () {
            require __DIR__ . '/api/auth/api.php';
            Route::group([
                'middleware' => [AuthService::AUTH_MIDDLEWARE_NAME],
            ], function () {

            });
        }
    );

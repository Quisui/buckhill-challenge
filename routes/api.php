<?php

use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Auth\Admin\RegisterController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\Admin\PasswordUpdateController;
use App\Http\Controllers\Api\V1\Auth\PasswordUpdateController as AuthPasswordUpdateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('admin/login', LoginController::class);
Route::post('user/login', LoginController::class);

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin'], function () {
        Route::put('forgot-password', PasswordUpdateController::class);
        Route::post('register/users', RegisterController::class);
        Route::apiResource('create', UserController::class);
        Route::post('logout', LogoutController::class);
    });
    Route::group(['prefix' => 'user'], function () {
        Route::post('user/logout', LogoutController::class);
        Route::put('forgot-password', AuthPasswordUpdateController::class);
    });
});

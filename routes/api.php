<?php

use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Auth\Admin\RegisterController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use Illuminate\Http\Request;
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

Route::group(["middleware" => ['auth:api']], function () {
    Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin'], function () {
        Route::post('register/users', RegisterController::class);
        Route::apiResource('create', UserController::class);
    });
});

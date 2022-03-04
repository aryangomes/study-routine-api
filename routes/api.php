<?php

use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\Auth\RegisterUserController;
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

/**
 * Register Route
 */
Route::post('/register', RegisterUserController::class);


/**
 * Auth Routes
 */
Route::post('/login', LoginController::class);


/**
 * Sanctum Middleware Routes
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

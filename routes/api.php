<?php

use App\Application\Api\Controllers\v1\Authentication\EmailVerification\EmailVerify;
use App\Application\Api\Controllers\v1\Authentication\EmailVerification\ResendEmailVerification;
use App\Application\Api\Controllers\v1\Authentication\LoginController;
use App\Application\Api\Controllers\v1\Authentication\LogoutController;
use App\Application\Api\Controllers\v1\Authentication\RegisterUserController;

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
Route::post('/register', RegisterUserController::class)->name('auth.register');


/**
 * Auth Routes
 */
Route::post('/login', LoginController::class)->name('auth.login');
Route::middleware('auth:sanctum')->get('/logout', LogoutController::class)->name('auth.logout');

/**
 * Email verification Routes
 */
Route::get('/email/verify/{id}/{hash}', EmailVerify::class)->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', ResendEmailVerification::class)->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');


/**
 * Sanctum Middleware Routes
 */
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    /**
     * User Resource Controller Routes
     */


    /**
     *  Subject Resource Controller Routes
     */
});

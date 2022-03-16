<?php

use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\Auth\LogoutController;
use App\Http\Controllers\Api\v1\Auth\RegisterUserController;
use App\Http\Controllers\Api\v1\ExamTestsController;
use App\Http\Controllers\Api\v1\SubjectController;
use App\Http\Controllers\Api\v1\UserController;
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
Route::post('/register', RegisterUserController::class)->name('auth.register');


/**
 * Auth Routes
 */
Route::post('/login', LoginController::class)->name('auth.login');
Route::middleware('auth:sanctum')->get('/logout', LogoutController::class)->name('auth.logout');

/**
 * Sanctum Middleware Routes
 */
Route::middleware('auth:sanctum')->group(function () {

    /**
     * User Resource Controller Routes
     */
    Route::controller(UserController::class)
        ->name('users.')
        ->group(function () {
            Route::get('/users', 'show')->name('show');
            Route::patch('/users', 'update')->name('update');
            Route::delete('/users', 'destroy')->name('destroy');
        });

    /**
     *  Subject Resource Controller Routes
     */
    Route::apiResource('subjects', SubjectController::class);

    /**
     *  Exam Tests Resource Controller Routes
     */
    Route::prefix('exams')->group(function () {
        Route::apiResource('tests', ExamTestsController::class);
    });
});

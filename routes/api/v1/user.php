<?php

use App\Application\Api\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)
    ->name('users.')
    ->group(function () {
        Route::get('/users', 'show')->name('show');
        Route::patch('/users', 'update')->name('update');
        Route::delete('/users', 'destroy')->name('destroy');
    });

<?php

use App\Application\Api\Controllers\v1\DailyActivityController;

use Illuminate\Support\Facades\Route;



Route::apiResource('dailyActivities', DailyActivityController::class)
    ->parameter('dailyActivities', 'dailyActivity');

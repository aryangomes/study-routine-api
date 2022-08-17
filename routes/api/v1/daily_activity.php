<?php

use App\Application\Api\Controllers\v1\DailyActivityController;

use Illuminate\Support\Facades\Route;

Route::get('dailyActivities/activitables', [DailyActivityController::class, 'getActivitables'])
    ->name('dailyActivities.activitables');

Route::apiResource('dailyActivities', DailyActivityController::class)
    ->parameter('dailyActivities', 'dailyActivity');

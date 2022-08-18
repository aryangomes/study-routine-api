<?php

use App\Application\Api\Controllers\v1\DailyActivityController;
use App\Application\Api\Controllers\v1\Notifications\MarkANotificationAsReadController;
use Illuminate\Support\Facades\Route;

Route::prefix('unread')->group(function () {
    Route::get(
        'userDailyActivities',
        [
            DailyActivityController::class,
            'getUnreadUserDailyActivitiesNotifications'
        ]
    )
        ->name('notifications.unread.dailyActivities.userDailyActivities');
});

Route::get('{notification}/read', MarkANotificationAsReadController::class)
    ->name('notifications.notification.markAsRead');

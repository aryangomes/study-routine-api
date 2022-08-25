<?php

use App\Application\Api\Controllers\v1\DailyActivityController;
use App\Application\Api\Controllers\v1\Notifications\MarkANotificationAsReadController;
use Illuminate\Support\Facades\Route;
use App\Application\Api\Controllers\v1\Notifications\GetNotificationsTypesController;
use App\Application\Api\Controllers\v1\Notifications\MarkAllNotificationsAsReadByTypeController;
use App\Application\Api\Controllers\v1\Notifications\MarkAllNotificationsAsReadController;

Route::prefix('unread')->group(function () {
    Route::get(
        'userDailyActivities',
        [
            DailyActivityController::class,
            'getUnreadUserDailyActivitiesNotifications'
        ]
    )
        ->name('notifications.unread.dailyActivities.userDailyActivities');



    Route::get('markAllAsRead', MarkAllNotificationsAsReadController::class)
        ->name('notifications.unread.markAllAsRead');

    Route::get('{notification}/markAsRead', MarkANotificationAsReadController::class)
        ->name('notifications.unread.notification.markAsRead');

    Route::get(
        'types/{type}/markAllAsRead',
        MarkAllNotificationsAsReadByTypeController::class
    )
        ->name('notifications.unread.type.markAllAsRead');
});



Route::get('types', GetNotificationsTypesController::class)
    ->name('notifications.types');

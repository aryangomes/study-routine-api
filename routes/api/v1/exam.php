<?php

use App\Application\Api\Controllers\v1\ExamController;

Route::get('exams/notifications/unread/nearbyEffectiveDate', [ExamController::class, 'getUnreadNearbyEffectiveDateNotifications'])
    ->name('exams.notifications.unread.nearbyEffectiveDate');

<?php

namespace App\Application\Api\Controllers\v1\Notifications;

use App\Application\Api\Controllers\v1\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class MarkANotificationAsReadController extends BaseApiController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(DatabaseNotification $notification)
    {

        try {
            $notification->markAsRead();

            return response()->json(
                ['response' => __('notifications.notification.markedAsRead')]
            );
        } catch (\Exception $exception) {
            throw new \Exception("It was not possible to mark the notification as read.");
        }
    }
}

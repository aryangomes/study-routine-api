<?php

namespace App\Application\Api\Controllers\v1\Notifications;

use App\Application\Api\Controllers\v1\BaseApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Response;
use Domain\User\Models\User;

class MarkAllNotificationsAsReadController extends BaseApiController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $user = User::find(auth()->user()->id);


        try {
            $user->unreadNotifications->markAsRead();

            return response()->json(
                ['response' => __('notifications.unread.notifications.markedAllAsRead')]
            );
        } catch (\Exception $exception) {
            throw new \Exception("It was not possible to mark all notifications as read.");
        }
    }
}

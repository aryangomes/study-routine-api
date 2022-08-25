<?php

namespace App\Application\Api\Controllers\v1\Notifications;

use App\Application\Api\Controllers\v1\BaseApiController;
use Illuminate\Http\Request;
use Domain\User\Models\User;
use Illuminate\Http\Response;
use Support\Utils\NotificationsTypes;

class MarkAllNotificationsAsReadByTypeController extends BaseApiController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $type)
    {
        $user = User::find(auth()->user()->id);

        $notificationType = NotificationsTypes::getNotificationsTypes()[$type];


        $notificationsByType = $user->unreadNotifications()
            ->where('type', $notificationType);


        if (!$notificationsByType) {
            return response()->json(
                ['response' =>
                __('notifications.errors.type_not_found')],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            $notificationsByType->update(['read_at' => now()]);

            return response()->json(
                ['response' =>
                __('notifications.unread.notifications.markedAllByTypeAsRead')]
            );
        } catch (\Exception $exception) {

            throw new \Exception("It was not possible to mark these notifications as read.");
        }
    }
}

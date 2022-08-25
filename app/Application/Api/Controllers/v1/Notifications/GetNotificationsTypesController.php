<?php

namespace App\Application\Api\Controllers\v1\Notifications;

use App\Application\Api\Controllers\v1\BaseApiController;
use App\Domain\DailyActivity\Notifications\UserDailyActivityNotification;
use App\Domain\Exam\Notifications\NearbyEffectiveDateNotification;
use Illuminate\Http\Request;
use Support\Utils\NotificationsTypes;

class GetNotificationsTypesController extends BaseApiController
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->json(
            NotificationsTypes::getKeysOfNotificationsTypes()
        );
    }
}

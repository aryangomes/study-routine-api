<?php

namespace App\Application\Api\Controllers\v1;

use App\Domain\Exam\Services\ExamService;
use Illuminate\Http\Request;
use App\Application\Api\Resources\Exam\Notifications\NearbyEffectiveDateCollection;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;

class ExamController extends BaseApiController
{

    public function __construct(private ExamService $examService)
    {
    }

    /**
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function getUnreadNearbyEffectiveDateNotifications()
    {

        $unreadNearbyEffectiveDateNotifications = $this->examService->getUnreadNearbyEffectiveDateNotifications();
        return response()->json(new NearbyEffectiveDateCollection($unreadNearbyEffectiveDateNotifications));
    }
}

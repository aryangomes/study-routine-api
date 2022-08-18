<?php

declare(strict_types=1);

namespace App\Domain\Exam\Services;


use App\Domain\Exam\Notifications\NearbyEffectiveDateNotification;
use App\Support\Services\CrudModelOperationsService;
use Illuminate\Support\Collection;
use Domain\User\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Domain\Exam\Models\Exam;

class ExamService extends CrudModelOperationsService
{
    public function __construct()
    {
        parent::__construct(new Exam());
    }



    public function getUnreadNearbyEffectiveDateNotifications(): Collection
    {
        $user = User::find(auth()->user()->id);

        $userNearbyEffectiveDateNotifications =
            $user->unreadNotifications->filter(fn ($notification) => $notification->type === NearbyEffectiveDateNotification::class);

        return $userNearbyEffectiveDateNotifications;
    }

    public function markReadNearbyEffectiveDateNotification(DatabaseNotification $nearbyEffectiveDateNotification)
    {
        try {
            $nearbyEffectiveDateNotification->markAsRead();
        } catch (\Exception $exception) {
            throw new \Exception("It was not possible to mark the notification as read.");
        }
    }
}

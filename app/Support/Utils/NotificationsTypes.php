<?php

declare(strict_types=1);

namespace Support\Utils;

use App\Domain\DailyActivity\Notifications\UserDailyActivityNotification;
use App\Domain\Exam\Notifications\NearbyEffectiveDateNotification;

class NotificationsTypes
{

    /**
     * @property array $TYPE_NOTIFICATIONS
     */
    private const NOTIFICATIONS_TYPES = [
        'nearbyEffectiveDate' => NearbyEffectiveDateNotification::class,
        'userDailyActivity' => UserDailyActivityNotification::class,
    ];

    /**
     * Get the value of the types of Notifications
     */
    public static function getNotificationsTypes(): array
    {
        return (self::NOTIFICATIONS_TYPES);
    }

    /**
     * Get the value of the types of Notifications
     */
    public static function getKeysOfNotificationsTypes(): array
    {
        return array_keys(self::NOTIFICATIONS_TYPES);
    }
}

<?php

namespace App\Domain\DailyActivity\Actions;

use App\Domain\DailyActivity\Models\DailyActivity;

class GetUsersWithDailyActivities
{


    public function __invoke()
    {
        $dailyActivitiesToday = DailyActivity::with('activitable.subject.user')->today()->get();

        $usersWithDailyActivitiesToday =
            $dailyActivitiesToday->groupBy('activitable.subject.user.id')->toBase();

        return $usersWithDailyActivitiesToday;
    }
}

<?php

namespace App\Console;

use App\Domain\DailyActivity\Actions\GetUsersWithDailyActivities;
use App\Domain\DailyActivity\Jobs\SendUsersDailyActivitiesNotifications;
use App\Domain\Exam\Jobs\SendNearbyEffectiveDateNotifications;
use App\Domain\Exam\Notifications\NearbyEffectiveDateNotification;
use Domain\Exam\Models\Exam;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {


        $schedule->job(new SendNearbyEffectiveDateNotifications())->daily();

        $schedule->job(new SendUsersDailyActivitiesNotifications())->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

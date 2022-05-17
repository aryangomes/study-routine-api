<?php

namespace App\Console;

use App\Domain\Exam\Jobs\SendNotification;
use App\Domain\Exam\Notifications\NearbyEffectiveDate;
use Carbon\Carbon;
use Domain\Exam\Models\Exam;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

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

        $schedule->call(function () {


            $examsExpirationDateNotifications = Exam::oneWeekToEffectiveDate()->get();

            $examsExpirationDateNotifications->each(
                fn ($exam) =>
                $exam->subject->user->notify(new NearbyEffectiveDate($exam))


            );
        })
            ->daily();
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

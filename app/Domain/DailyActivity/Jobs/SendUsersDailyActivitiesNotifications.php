<?php

namespace App\Domain\DailyActivity\Jobs;

use App\Domain\DailyActivity\Actions\GetUsersWithDailyActivities;
use App\Domain\DailyActivity\Notifications\UserDailyActivityNotification;
use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendUsersDailyActivitiesNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private Collection $dailyActivitiesToday;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $usersDailyActivities = new GetUsersWithDailyActivities();
        $this->dailyActivitiesToday = $usersDailyActivities();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->dailyActivitiesToday->each(function ($userDailyActivities, $userId) {
            $user = User::find($userId);
            $userDailyActivities->each(
                fn ($userDailyActivity) =>
                $user->notify(new UserDailyActivityNotification($userDailyActivity->toArray()))
            );
        });
    }
}

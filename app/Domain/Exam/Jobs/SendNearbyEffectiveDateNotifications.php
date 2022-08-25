<?php

namespace App\Domain\Exam\Jobs;

use App\Domain\Exam\Notifications\NearbyEffectiveDateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Domain\Exam\Models\Exam;

class SendNearbyEffectiveDateNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private Collection $examsExpirationDateNotifications;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->examsExpirationDateNotifications = Exam::oneWeekToEffectiveDate()->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->examsExpirationDateNotifications->each(
            fn ($exam) =>
            $exam->subject->user->notify(
                new NearbyEffectiveDateNotification($exam)
            )

        );
    }
}

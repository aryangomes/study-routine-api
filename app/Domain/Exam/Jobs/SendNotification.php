<?php

namespace App\Domain\Exam\Jobs;

use App\Domain\Exam\Notifications\NearbyEffectiveDate;
use Domain\Exam\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Exam $exam)
    {

        logger(
            'SendNotification',
            [
                '$exam' => $exam
            ]
        );
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Job done! Notification sended!', [
            'exam' => $this->exam
        ]);
        // $this->exam->user->notify(new NearbyEffectiveDate());
    }
}

<?php

namespace App\Domain\Exam\Mail;

use Domain\Exam\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NearbyEffectiveDate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private Exam $exam)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.exams.nearby_effective_date', [
            'exam' => $this->exam,
            'subject' => $this->exam->subject,
            'user' => $this->exam->subject->user,
        ]);
    }
}

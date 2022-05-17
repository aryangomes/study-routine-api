<?php

namespace App\Domain\Exam\Notifications;

use App\Domain\Exam\Mail\NearbyEffectiveDate as MailNearbyEffectiveDate;
use Domain\Exam\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class NearbyEffectiveDate extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Exam $exam)
    {
        //

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return Mailable
     */
    public function toMail($notifiable)
    {

        $subject =    $this->generateSubject();

        return (new MailNearbyEffectiveDate($this->exam))
            ->subject($subject)
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'exam_id' => $this->exam->id,
            'subject_id' => $this->exam->subject_id,
            'user_id' => $this->exam->subject->user_id,
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'exam_id' => $this->exam->id,
            'subject_id' => $this->exam->subject_id,
            'user_id' => $this->exam->subject->user_id,
        ];
    }

    private function generateSubject(): string
    {
        $examableClass = new $this->exam->examable_type;

        $examableClassName = get_short_class_name($examableClass);

        $subject = "Your Exam {$examableClassName} is coming";

        return $subject;
    }
}

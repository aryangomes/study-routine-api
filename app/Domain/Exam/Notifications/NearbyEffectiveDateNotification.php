<?php

namespace App\Domain\Exam\Notifications;

use App\Domain\Exam\Mail\NearbyEffectiveDate;
use Domain\Exam\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class NearbyEffectiveDateNotification extends Notification implements ShouldQueue
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

        return (new NearbyEffectiveDate($this->exam))
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
            'id' => $this->exam->id,
            'subject_id' => $this->exam->subject->id,
            'subject_name' => $this->exam->subject->name,
            'user_name' => $this->exam->subject->user->name,
            'effective_date' => $this->exam->effective_date,
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
        return $this->toArray($notifiable);
    }

    private function generateSubject(): string
    {
        $examableClass = new $this->exam->examable_type;

        $examableClassName = get_short_class_name($examableClass);

        $subject = "Your Exam {$examableClassName} is coming";

        return $subject;
    }
}

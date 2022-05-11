<?php

namespace App\Domain\Exam\Notifications;

use Domain\Exam\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NearbyEffectiveDate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private Exam $exam)
    {
        //
        logger(
            'NearbyEffectiveDate',
            [
                'exam' => $this->exam
            ]
        );
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Exam effective date warning")
            ->greeting("Hello, {$this->exam->subject->user->name}!")
            ->line("This is a notification about your Exam, that will be in {$this->exam->effective_date}")->line('Thank you for using our application!');
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
            'exam_id' => $this->exam->id
        ];
    }
}

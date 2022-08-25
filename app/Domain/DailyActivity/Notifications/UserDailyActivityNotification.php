<?php

namespace App\Domain\DailyActivity\Notifications;

use App\Domain\DailyActivity\Models\DailyActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserDailyActivityNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private array $dailyActivity)
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
        return ['database'];
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
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
            'id' => $this->dailyActivity['id'],
            'date_of_activity' => $this->dailyActivity['date_of_activity'],
            'start_time' => $this->dailyActivity['start_time'],
            'end_time' => $this->dailyActivity['end_time'],
            'activitable_type' => DailyActivity::getActivityType($this->dailyActivity['activitable_type']),
            'activitable_id' => $this->dailyActivity['activitable_id'],
            'subject_id' => $this->dailyActivity['activitable']['subject']['id'],
            'subject_name' => $this->dailyActivity['activitable']['subject']['name'],
        ];
    }

    public function toDatabase($notifiable)
    {
        return $this->toArray($notifiable);
    }
}

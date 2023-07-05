<?php

namespace App\Notifications;

use App\Models\EventProgram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventProgramNotification extends Notification
{
    use Queueable;

    public $program;
    public $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(EventProgram $program, string $status)
    {
        $this->program = $program;
        $this->status = $status;
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
       $message = $this->status == 'success' ? 'Payment successful. Refresh the page and download the Event Program for the event '.$this->program->event_name : 'An error occurred with payment of the Event program for the event '.$this->program->event_name;
        return [
            'type' => 'Program Notification',
            'program_id' => $this->program->id,
            'message' => $message
        ];
    }
}

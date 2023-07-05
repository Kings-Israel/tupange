<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\EventTask;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEventTaskNotification extends Mailable
{
   use Queueable, SerializesModels;

   public $event;
   public $task;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct(Event $event, EventTask $task)
   {
      $this->event = $event;
      $this->task = $task;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
      return $this->markdown('mail.event-task-notification')
      ->subject('Reminder to fulfil assigned task for event '.$this->event->event_name)
      ->with(['content' => 'This is a reminder notification to fulfil the task '.$this->task->task.' for the above mentioned event before the date '.Carbon::parse($this->task->date_due)->format('d M, Y')]);
   }
}

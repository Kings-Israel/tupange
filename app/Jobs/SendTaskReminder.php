<?php

namespace App\Jobs;

use App\Models\EventUser;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendEventTaskNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendTaskReminder implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public $event;
   public $task;

   /**
    * Create a new job instance.
    *
    * @return void
    */
   public function __construct($event, $task)
   {
      $this->event = $event;
      $this->task = $task;
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle()
   {
      $user = EventUser::where('event_id', $this->task->event_id)->where('names', $this->task->person_responsible)->first();
      if ($user) {
         Mail::to($user->email)->send(new SendEventTaskNotification($this->event, $this->task));
      }
   }
}

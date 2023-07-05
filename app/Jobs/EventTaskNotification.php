<?php

namespace App\Jobs;

use App\Mail\SendEventTaskNotification;
use App\Models\Event;
use App\Models\EventTask;
use App\Models\EventUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EventTaskNotification implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   /**
    * Create a new job instance.
    *
    * @return void
    */
   public function __construct()
   {

   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle()
   {
      $tasks = EventTask::where('status', 'Open')->orWhere('status', 'In Progress')->get();
      foreach ($tasks as $task) {
         $user = EventUser::where('event_id', $task->event_id)->where('names', $task->person_responsible)->first();
         
         if ($task->notify_due == 'Daily' && now()->diffInDays($task->updated_at) <= 1) {
            if ($user) {
               $event = Event::find($task->event_id);
               Mail::to($user->email)->send(new SendEventTaskNotification($event, $task));
            }
         } elseif ($task->notify_due == 'Weekly' && now()->diffInDays($task->updated_at) == 7) {
            if ($user) {
               $event = Event::find($task->event_id);
               Mail::to($user->email)->send(new SendEventTaskNotification($event, $task));
            }
         } elseif ($task->notify_due == 'Monthly' && now()->diffInDays($task->updated_at) == 30) {
            if ($user) {
               $event = Event::find($task->event_id);
               Mail::to($user->email)->send(new SendEventTaskNotification($event, $task));
            }
         }
      }
   }
}

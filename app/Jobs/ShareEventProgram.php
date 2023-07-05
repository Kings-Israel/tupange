<?php

namespace App\Jobs;

use App\Mail\ShareEventProgram as MailShareEventProgram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ShareEventProgram implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public $email;
   public $program;
   public $event_name;

   /**
    * Create a new job instance.
    *
    * @return void
    */
   public function __construct($email, $program, $event_name)
   {
      $this->email = $email;
      $this->program = $program;
      $this->event_name = $event_name;
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle()
   {
      Mail::to($this->email)->send(new MailShareEventProgram($this->program, $this->event_name));
   }
}

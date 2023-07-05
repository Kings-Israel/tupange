<?php

namespace App\Jobs;

use App\Mail\EventTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEventTicket implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   protected $email;
   protected $subject;
   protected $content;
   protected $ticket;

   /**
    * Create a new job instance.
    *
    * @return void
    */
   public function __construct($email, $subject, $content, $ticket)
   {
      $this->email = $email;
      $this->subject = $subject;
      $this->content = $content;
      $this->ticket = $ticket;
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle()
   {
      Mail::to($this->email)->send(new EventTicket($this->subject, $this->content, $this->ticket));
   }
}

<?php

namespace App\Jobs;

use App\Mail\SendEventUserInvite as MailSendEventUserInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEventUserInvite implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public $data;

   /**
    * Create a new job instance.
    *
    * @return void
    */
   public function __construct(array $data)
   {
      $this->data = $data;
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle()
   {
      Mail::to($this->data['email'])->send(new MailSendEventUserInvite($this->data['email'], $this->data['subject'], $this->data['message'], $this->data['event_id'], $this->data['role_id']));
   }
}

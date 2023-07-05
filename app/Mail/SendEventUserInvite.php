<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEventUserInvite extends Mailable
{
   use Queueable, SerializesModels;

   public $email;
   public $subject;
   public $message;
   public $event_id;
   public $role_id;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct($email, $subject, $message, $event_id, $role_id)
   {
      $this->email = $email;
      $this->subject = $subject;
      $this->message = $message;
      $this->event_id = $event_id;
      $this->role_id = $role_id;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
      return $this->markdown('mail.event-user-invite')
         ->subject($this->subject)
         ->with([
            'content' => $this->message,
            'url' => config('services.app_url.url').'/event/'.$this->event_id.'/user/'.$this->email.''
         ]);
   }
}

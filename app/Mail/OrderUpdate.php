<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderUpdate extends Mailable
{
   use Queueable, SerializesModels;

   public $email;
   public $subject;
   public $content;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct($email, $subject, $content)
   {
      $this->email = $email;
      $this->subject = $subject;
      $this->content = $content;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
      return $this->markdown('mail.order-update')
         ->subject($this->subject)
         ->with([
            'content' => $this->content,
            'url' => config('services.app_url.url')
         ]);
   }
}

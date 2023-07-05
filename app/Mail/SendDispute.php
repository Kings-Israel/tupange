<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDispute extends Mailable
{
   use Queueable, SerializesModels;

   public $name;
   public $email;
   public $content;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct(string $name, string $email, $content)
   {
      $this->name = $name;
      $this->email = $email;
      $this->content = $content;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
      return $this->markdown('mail.dispute')
         ->subject('Dispute/Comment filed by '.$this->name)
         ->with(['name' => $this->name, 'email' => $this->email, 'content' => $this->content]);
   }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventTicket extends Mailable
{
   use Queueable, SerializesModels;

   protected $content;
   protected $ticket;
   public $subject;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct($subject, $content, $ticket)
   {
      $this->subject = $subject;
      $this->content = $content;
      $this->ticket = $ticket;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
      return $this->markdown('mail.event-ticket')
         ->subject($this->subject)
         ->with(['content' => $this->content])
         ->attachData($this->ticket, 'ticket.pdf');
   }
}

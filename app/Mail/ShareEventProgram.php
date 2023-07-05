<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShareEventProgram extends Mailable
{
   use Queueable, SerializesModels;

   public $program;
   public $event_name;

   /**
    * Create a new message instance.
    *
    * @return void
    */
   public function __construct($program, $event_name)
   {
      $this->program = $program;
      $this->event_name = $event_name;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
      return $this->markdown('mail.event-program')
         ->subject('Program for the event, '. $this->event_name)
         ->with(['content' => 'Please find the program for the above mentioned event attached to this mail.'])
         ->attachData($this->program, $this->event_name.'-program.pdf');
   }
}

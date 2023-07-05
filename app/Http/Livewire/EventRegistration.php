<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Register;
use App\Models\RegisterTicket;

class EventRegistration extends Component
{
   public $event;

   public function deleteGuest(Register $guest)
   {
      $guest->delete();
      $this->event->refresh();

   }

   public function render()
   {
      $registered = Register::where('event_id', $this->event->id)->get();
      $tickets = RegisterTicket::where('event_id', $this->event->id)->get();
      return view('livewire.event-registration', compact('registered', 'tickets'));
   }
}

<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\EventGuestDetail;
use App\Models\EventUser;
use App\Models\User;
use App\Models\UserRole;
use Livewire\Component;
use Livewire\WithPagination;
use PDF;
use App\Jobs\SendEventTicket;

class EventGuestList extends Component
{
   use WithPagination;

   public $event;
   public $perPage = 10;
   public $i = 1;
   public $code = '';
   public $phone_number = '';
   public $name = '';
   public $email = '';
   public $search = '';

   public function paginationView()
   {
      return 'layouts.custom-paginate';
   }
   public function updatingPerPage()
   {
      $this->resetPage();
   }

   public function updatingCode()
   {
      $this->resetPage();
   }

   public function updatingPhone_number()
   {
      $this->resetPage();
   }

   public function updatingName()
   {
      $this->resetPage();
   }

   public function updatingEmail()
   {
      $this->resetPage();
   }

   public function getInvitedGuestsCount()
   {
      return $this->event->loadCount(['event_guests' => function($query) {
         $query->where('event_guest_details.status', 'Invited');
      }]);
   }

   public function getConfirmedGuestsCount()
   {
      return $this->event->loadCount(['event_guests' => function($query) {
         $query->where('event_guest_details.status', 'Confirmed');
      }]);
   }

   public function getDefaultGuestsCount()
   {
      return $this->event->loadCount(['event_guests' => function($query) {
         $query->where('event_guest_details.status', 'Default');
      }]);
   }

   public function getAttendedGuestsCount()
   {
      return $this->event->loadCount(['event_guests' => function($query) {
         $query->where('event_guest_details.status', 'Attended');
      }]);
   }

   public function getPaidTicketsCount()
   {
      return $this->event->loadCount(['event_guests' => function($query) {
         $query->where('event_guest_details.is_paid', true);
      }]);
   }

   public function getPaidTicketsAmount()
   {
      $ticket_paid_amount = 0;
      foreach ($this->event->event_guests as $guest) {
         if ($guest->is_paid) {
            $ticket_paid_amount += (double) $guest->ticket_price * (int) $guest->guests;
         }
      }

      return $ticket_paid_amount;
   }

   public function sendInvite(EventGuestDetail $guest)
   {
      if ($guest->email != null) {
         $data['email'] = $guest->email;
         $data['guest_name'] = $guest->first_name.' '.$guest->last_name;
         $data['subject'] = $guest->event->event_name.' ticket.';
         $data['content'] = 'Your ticket for the event '.$guest->event->event_name.' has been attached to this email.';

         $pdf = PDF::loadView('partials.event-guest-ticket-pdf', ['guest' => $guest]);

         SendEventTicket::dispatchAfterResponse($data['email'], $data['subject'], $data['content'], $pdf->output());

         $guest->update([
            'ticketSent' => true
         ]);

         return back()->with('success', 'Ticket Sent');
      } else {
         return back()->with('error', "The guest does not have an email address");
      }
   }

   public function sendAllInvites(Event $event)
   {
      $guests = EventGuestDetail::where('email', '!=', null)->where('ticketSent', false)->where('event_id', $event->id)->get();

      collect($guests)->each(function ($guest) {
         $data['email'] = $guest->email;
         $data['guest_name'] = $guest->first_name.' '.$guest->last_name;
         $data['subject'] = $guest->event->event_name.' ticket.';
         $data['content'] = 'Your ticket for the event '.$guest->event->event_name.' has been attached to this email.';

         $pdf = PDF::loadView('partials.event-guest-ticket-pdf', ['guest' => $guest]);

         SendEventTicket::dispatchAfterResponse($data['email'], $data['subject'], $data['content'], $pdf->output());

         $guest->update([
            'ticketSent' => true
         ]);
      });

      return back()->with('success', 'Tickets Sent');
   }

   public function deleteGuest($id)
   {
      $guest = EventGuestDetail::find($id);

      EventUser::where('email', $guest->email)->delete();

      $user = User::where('email', $guest->email)->first();

      if ($user) {
         $role = UserRole::where('user_id', $user->id)->where('event_id', $this->event->id)->first();

         if ($role) {
            $role->delete();
         }
      }

      $guest->delete();

      session()->flash('success', 'Guest deleted successfully');
   }

   public function render()
   {
      $guests = EventGuestDetail::where('event_id', $this->event->id)
                                 ->where(function($query) {
                                    $query->where('first_name', 'LIKE', '%'.$this->search.'%')
                                       ->orWhere('last_name', 'LIKE', '%'.$this->search.'%')
                                       ->orWhere('email', 'LIKE', '%'.$this->search.'%')
                                       ->orWhere('phone_number', 'LIKE', '%'.$this->search.'%')
                                       ->orWhere('barcode', 'LIKE', '%'.$this->code.'%');
                                 })
                                 ->orderByRaw("FIELD(status , 'Attended') DESC")
                                 ->get();

      collect($guests)->each(function ($guest) {
         $guestRole = EventUser::where('email', $guest->email)->first();
         $guest['role'] = $guestRole;
      });

      $guests = $guests->sortBy([
         fn ($a, $b) => $b['role'] > $a['role'],
      ]);

      return view('livewire.event-guest-list', [
         'guests' => $guests,
         'event' => $this->event
      ]);
   }
}

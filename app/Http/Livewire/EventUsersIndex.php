<?php

namespace App\Http\Livewire;

use App\Jobs\SendEventUserInvite;
use App\Models\EventUser;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Notifications\UserEventNotification;
use Livewire\Component;

class EventUsersIndex extends Component
{
   public $event;
   public $i = 1;

   public function deleteUser(EventUser $user)
   {
      UserRole::where('user_id', $user->user_id)->where('event_id', $user->event_id)->delete();

      $user->delete();

      $this->event->refresh();
   }

   public function sendInvite(EventUser $user)
   {
      $oldUser = User::where('email', $user->email)->first();
      $role = Role::where('name', $user->role)->first();
      if ($oldUser) {
         $alreadyAssigned = UserRole::where('user_id', $user->user_id)->where('event_id', $this->event->id)->first();
         if (! $alreadyAssigned) {
            UserRole::create([
               'event_id' => $this->event->id,
               'user_id' => $oldUser->id,
               'role_id' => $role->id
            ]);
         }

         $oldUser->notify(new UserEventNotification($this->event->id, $this->event->event_name, $this->event->user->f_name.' '.$this->event->user->l_name, $role->name));
      }

      $data = [
         'email' => $user->email,
         'subject' => 'Invitation to collaborate in Event Management',
         'message' => 'This is an invitation sent by '. $this->event->user->f_name.' '.$this->event->user->l_name .' to you for assistance in the management of the event '. $this->event->event_name . ' in the role '. $user->role .'.<br /><br /> Click on the button below to login and view the event details.',
         'event_id' => $this->event->id,
         'role_id' => $role->id,
      ];

      SendEventUserInvite::dispatchAfterResponse($data);

      session()->put('success', 'Invite Sent');

      $user->isSent = 1;
      $user->save();
      $this->event->refresh();
   }

   public function render()
   {
      $eventUsers = $this->event->load('event_users');
      $event = $this->event;
      return view('livewire.event-users-index', compact('event', 'eventUsers'));
   }
}

<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\EventUser;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
   use HandlesAuthorization;

   /**
    * Determine whether the user can view any models.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function viewAny(User $user)
   {
      //
   }

   /**
    * Determine whether the user can view the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\Event  $event
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function view(User $user, Event $event)
   {
      $role = UserRole::where([
         'user_id' => $user->id,
         'event_id' => $event->id,
         'role_id' => 1
      ]);

      if ($role) {
         return true;
      }

      return $user->id === (int) $event->user_id;
   }

   /**
    * Determine whether the user can create models.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function create(User $user)
   {
      //
   }

   /**
    * Determine whether the user can update the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\Event  $event
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function update(User $user, Event $event)
   {
      return $user->id === (int) $event->user_id;
   }

   /**
    * Determine whether the user can delete the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\Event  $event
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function delete(User $user, Event $event)
   {
      //
   }

   /**
    * Determine whether the user can restore the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\Event  $event
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function restore(User $user, Event $event)
   {
      //
   }

   /**
    * Determine whether the user can permanently delete the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\Event  $event
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function forceDelete(User $user, Event $event)
   {
      //
   }

   public function viewTasks(User $user, Event $event)
   {
      $role = UserRole::where([
         'user_id' => $user->id,
         'event_id' => $event->id
      ])->first();

      if ($role) {
         return true;
      }

      return $user->id === (int) $event->user_id;
   }

   public function viewProgram(User $user, Event $event)
   {
      $role = UserRole::where([
         'user_id' => $user->id,
         'event_id' => $event->id,
         'role_id' => 2
      ])->first();

      if ($role) {
         return true;
      }

      return $user->id === (int) $event->user_id;
   }

   public function viewOrders(User $user, Event $event)
   {
      $role = UserRole::where([
         'user_id' => $user->id,
         'event_id' => $event->id,
         'role_id' => 1
      ])->first();

      if ($role) {
         return true;
      }

      return $user->id === (int) $event->user_id;
   }

   public function viewGuestList(User $user, Event $event)
   {
      $role = UserRole::where([
         'user_id' => $user->id,
         'event_id' => $event->id,
         'role_id' => 1
      ])->first();

      if ($role) {
         return true;
      }

      return $user->id === (int) $event->user_id;
   }

   public function viewBudget(User $user, Event $event)
   {
      $role = UserRole::where([
         'user_id' => $user->id,
         'event_id' => $event->id,
         'role_id' => 1
      ])->first();

      if ($role) {
         return true;
      }

      return $user->id === (int) $event->user_id;
   }

   public function updateBudget(User $user, Event $event)
   {
      return $user->id === (int) $event->user_id;
   }
}

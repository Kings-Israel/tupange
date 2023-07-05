<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Event;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Notifications\DatabaseNotification;

class ClientNotifications extends Component
{
   public $notifications;
   public $notificationCount = 0;

   public function mount()
   {
      $this->notifications = collect([]);
      $this->getNotificationCount();
      $this->getNotifications();
   }

   public function markAsRead($notification)
   {
      if (auth()->guest()) {
         abort(HttpResponse::HTTP_FORBIDDEN);
      }

      $notification = DatabaseNotification::findOrFail($notification);
      $notification->markAsRead();

      // $this->goToOrder($notification);
   }

   public function goToOrder($notification)
   {
      $notification = DatabaseNotification::findOrFail($notification);
      $order = Order::find($notification->data['order_id']);

      if(! $order) {
         session()->flash('error', 'The order no longer exists');

         return redirect()->route('home');
      }

      return redirect()->route('client.orders.order', $order);

   }

   public function goToEvent($notification)
   {
      if (auth()->guest()) {
         abort(HttpResponse::HTTP_FORBIDDEN);
     }

     $notification = DatabaseNotification::findOrFail($notification);
     // $notification->markAsRead();

     $event = Event::find($notification->data['event_id']);

     if (! $event) {
        session()->flash('error', 'The event no longer exists');

        return redirect()->route('home');
     }

     return redirect()->route('events.show', $event->id);
   }

   public function goToProfile($notification)
   {
      if (auth()->guest()) {
         abort(HttpResponse::HTTP_FORBIDDEN);
      }

      // $notification = DatabaseNotification::findOrFail($notification);
      // $notification->markAsRead();

      return redirect()->route('user.profile.edit');
   }

   public function goToProgram($notification)
   {
      if (auth()->guest()) {
         abort(HttpResponse::HTTP_FORBIDDEN);
      }

      $notification = DatabaseNotification::findOrFail($notification);
      // $notification->markAsRead();

      return redirect()->route('client.program.show', $notification->data['program_id']);
   }

   public function markAllAsRead()
   {
      if (auth()->guest()) {
         abort(HttpResponse::HTTP_FORBIDDEN);
      }

      auth()->user()->unreadNotifications->markAsRead();
      $this->getNotificationCount();
      $this->getNotifications();
   }

   public function getNotificationCount()
   {
      $this->notificationCount = auth()->user()->unreadNotifications()->count();
   }

   public function getNotifications()
   {
      $this->notifications = auth()->user()->unreadNotifications()->get()->take(4);
   }

   public function render()
   {
      return view('livewire.client-notifications');
   }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Notifications\DatabaseNotification;

class VendorNotification extends Component
{
   public $vendor;
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
    }

   public function goToOrder($notification)
   {
      $notification = DatabaseNotification::findOrFail($notification);
      $order = Order::find($notification->data['order_id']);

      if(! $order) {
         session()->flash('error', 'The order no longer exists');

         return redirect()->route('vendor.dashboard');
      }

      return redirect()->route('vendor.orders.one', $order);

   }

   public function markAllAsRead()
    {
        if (auth()->guest()) {
            abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $this->vendor->unreadNotifications->markAsRead();
        $this->getNotificationCount();
        $this->getNotifications();
    }

   public function getNotificationCount()
   {
      $this->notificationCount = $this->vendor->unreadNotifications()->count();
   }

   public function getNotifications()
   {
      $this->notifications = $this->vendor->unreadNotifications()->get();
   }

   public function render()
   {
      return view('livewire.vendor-notification');
   }
}

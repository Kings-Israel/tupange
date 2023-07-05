<?php

namespace App\Http\Livewire;

use App\Jobs\SendOrderUpdateMail;
use App\Jobs\SendSms;
use App\Models\Order;
use App\Notifications\ClientNotification;
use Livewire\Component;

class OrderStatus extends Component
{
   public $order;

   public function receiveOrder(Order $order)
   {
      $order = Order::find($order->id);
      if(!$order) {
         return redirect()->route('vendor.orders.all')->with('error', 'The order no longer exists');
      }

      $order->status = 'Received';
      $order->save();

      $order->user->notify(new ClientNotification($order, 'Order Received'));

      if ($order->user->phone_verified_at != null) {
         SendSms::dispatchAfterResponse($order->user->phone_number, 'The vendor accepted your order for the service '.$order->service->service_title);
      }

      // Send email to client
      $data['email'] = $order->user->email;
      $data['subject'] = 'Accepted Order, Order '.$order->order_id;
      $data['content'] = 'The vendor for the service, '.$order->service->service_title.' accepted your order. Please login below to view the details.';

      SendOrderUpdateMail::dispatchAfterResponse($data);

      $this->emit('orderStatusChanged');

      $this->order->refresh();
   }

   public function declineOrder(Order $order)
   {
      $order = Order::find($order->id);
      if(!$order) {
         return redirect()->route('vendor.orders.all')->with('error', 'The order no longer exists');
      }

      $order->status = 'Declined';
      $order->save();

      $order->user->notify(new ClientNotification($order, 'Order Declined'));

      // Send email to client
      $data['email'] = $order->user->email;
      $data['subject'] = 'Declined Order, Order '.$order->order_id;
      $data['content'] = 'The vendor for the service, '.$order->service->service_title.' declined your order.';

      SendOrderUpdateMail::dispatchAfterResponse($data);

      $this->emit('orderStatusChanged');

      $this->order->refresh();
   }

   public function cancelOrder(Order $order)
   {
      $order = Order::find($order->id);
      if(!$order) {
         return redirect()->route('vendor.orders.all')->with('error', 'The order no longer exists');
      }

      $order->status = 'Cancelled';
      $order->save();

      $order->user->notify(new ClientNotification($order, 'Order Cancelled'));

      $this->emit('orderStatusChanged');

      $this->order->refresh();
   }

   public function completeOrder(Order $order)
   {
      $order->status = "Completed";
      $order->save();
      $this->emit('orderStatusChanged');
      $this->order->refresh();
   }

   public function archiveOrder(Order $order)
   {
      $order->status = 'Archived';
      $order->save();
      $this->emit('orderStatusChanged');
      $this->order->refresh();
   }

   public function render()
   {
      return view('livewire.order-status');
   }
}

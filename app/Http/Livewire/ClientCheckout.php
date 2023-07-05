<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class ClientCheckout extends Component
{
   public $orders;
   public $user;
   public $price;

   public function mount()
   {
      collect($this->orders);
   }

   public function deleteOrder(Order $order)
   {
      $this->orders = collect($this->orders)->filter(function($userOrder, $key) use ($order) {
         return $userOrder['id'] != $order->id;
      });
   }

   public function render()
   {
      return view('livewire.client-checkout');
   }
}

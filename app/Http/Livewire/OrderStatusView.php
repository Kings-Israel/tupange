<?php

namespace App\Http\Livewire;

use Livewire\Component;

class OrderStatusView extends Component
{
   public $order;

   protected $listeners = ['orderStatusChanged'];

   public function orderStatusChanged()
   {
      $this->order->refresh();
   }

   public function render()
   {
      return view('livewire.order-status-view');
   }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Messages;

class MessageSidenav extends Component
{
   public $search = '';

   public function render()
   {
      $orders = Messages::
         where('sent_from', auth()->user()->id)
         ->orWhere('sent_to', auth()->user()->id)
         ->orderBy('created_at', 'DESC')
         ->groupBy('order_id')
         ->when($this->search && $this->search != '', function($query) {
            return $query->where('messages.order_id', 'like', '%'.$this->search.'%');
         })
         ->get();

      return view('livewire.message-sidenav')->with('orders', $orders);
   }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class EventOrdersIndex extends Component
{
    use WithPagination;

    public $event;
    public $perPage = 20;

    public function paginationView()
    {
        return 'layouts.custom-paginate';
    }

   public function updatingPerPage()
   {
      $this->resetPage();
   }

    public function render()
    {
      $orders = $this->event->load(['orders' => function($query) {
         $query->where('orders.status', 'Paid')->orWhere('orders.status', 'Completed')->orWhere('orders.status', 'Sent')->orWhere('orders.status', 'Dispute')->orWhere('orders.status', 'Received');
      }]);

      return view('livewire.event-orders-index', ['orders' => $orders, 'event' => $this->event]);
    }
}

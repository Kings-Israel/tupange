<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Service;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class ClientOrdersView extends Component
{
   use WithPagination;

   public $perPage = 10;
   public $status = "All";
   public $selectedOrders;
   public $search = '';
   public $sort_order = 'asc';
   public $sort_attribute = '';

   public function paginationView()
   {
      return 'layouts.custom-paginate';
   }

   public function mount(Request $request)
   {
      if ($request->query('sort')) {
         $this->sort_attribute = $request->query('sort');
         $this->sort_order = 'ASC';
         if (strncmp($this->sort_attribute, '-', 1) === 0) {
            $this->sort_order = 'DESC';
            $this->sort_attribute = substr($this->sort_attribute, 1);
         }
      }
   }

   public function updatingSearch()
   {
      $this->resetPage();
   }

   public function updatingStatus()
   {
      $this->resetPage();
   }

   public function deleteOrder(Order $order)
   {
      // Delete all order messages
      $order->messages()->delete();

      // Delete all reviews
      if ($order->review()->exists()) {
         $order->review()->delete();
      }

      if ($order->status == 'Sent' || $order->status == 'Received') {
         $order->forceDelete();
      }

      $order->delete();

      session()->flash('success', 'Order Deleted');
   }

   public function render()
   {
      return view('livewire.client-orders-view', [
         'orders' => Order::with('payment')
         ->where('user_id', '=', auth()->user()->id)
         ->where('status', '!=', 'Archived')
         ->where('status', '!=', 'Cancelled')
         ->with('service', 'event', 'service_pricing', 'order_quotation')
         ->when($this->search && $this->search != '', function($query) {
            $query->where(function($query) {
                  $query->where('order_id', 'LIKE', '%'.$this->search.'%')
                        ->orWhereHas('service', function($query) {
                           $query->where('service_title', 'LIKE', '%'.$this->search.'%');
                        })
                        ->orWhereHas('event', function($query) {
                           $query->where('event_name', 'LIKE', '%'.$this->search.'%');
                        });
               });
         })
         ->when($this->status && $this->status != 'All', function($query) {
               return $query->where('status', $this->status);
         })
         ->when($this->sort_attribute && $this->sort_attribute != '', function($query) {
            if ($this->sort_attribute === 'service') {
               $query->orderBy(Service::select('service_title')->whereColumn('id', 'orders.service_id')->take(1), $this->sort_order);
            } elseif($this->sort_attribute === 'created_at' || $this->sort_attribute === 'id' || $this->sort_attribute === 'status') {
               $query->orderBy($this->sort_attribute, $this->sort_order);
            }
         })
         ->orderByRaw("FIELD(orders.status, 'Received', 'Sent') DESC")
         ->orderByRaw("FIELD(orders.status, 'Completed', 'Cancelled') ASC")
         ->orderBy('created_at', 'DESC')
         ->paginate($this->perPage)
      ]);
   }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class VendorOrdersView extends Component
{

   public $vendor;
   public $status = '';
   public $search = '';
   public $timeframe = 'last_year';
   public $category = '';
   public $categories;

   public function render()
   {
      $orders = $this->vendor->load(['services', 'orders' => function ($query) {
         $query->when($this->category && $this->category != '', function($query) {
            $query->where('services.category_id', $this->category);
         });
         $query->when($this->status && $this->status != 'All', function($query) {
            $query->where('orders.status', $this->status);
         });
         $query->when($this->search && $this->search != '', function($query) {
            $query->where('orders.order_id', 'like', '%'.$this->search.'%');
         });
         $query->when($this->timeframe && $this->timeframe == 'last_year', function($query) {
            $query->whereBetween('orders.created_at', [Carbon::now()->subYear(), Carbon::now()]);
         });
         $query->when($this->timeframe && $this->timeframe == 'last_month', function($query) {
            $query->whereBetween('orders.created_at', [Carbon::now()->subMonth(), Carbon::now()]);
         });
         $query->when($this->timeframe && $this->timeframe == 'last_week', function($query) {
            $query->whereBetween('orders.created_at', [Carbon::now()->subWeek(), Carbon::now()]);
         });
         $query->when($this->timeframe && $this->timeframe == 'last_two_years', function($query) {
            $query->whereBetween('orders.created_at', [Carbon::now()->subYears(2), Carbon::now()]);
         });
         $query->when($this->timeframe && $this->timeframe == 'last_four_years', function($query) {
            $query->whereBetween('orders.created_at', [Carbon::now()->subYears(4), Carbon::now()]);
         });
         $query->where('orders.status', '!=', 'Archived');
         $query->orderByRaw("FIELD(orders.status, 'Received', 'Sent') DESC");
         $query->orderByRaw("FIELD(orders.status, 'Completed', 'Cancelled') ASC");
         $query->orderBy('created_at', 'DESC');
      }]);

      return view('livewire.vendor-orders-view', ['orders' => $orders]);
   }

}

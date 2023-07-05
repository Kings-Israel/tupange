<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class VendorArchivedOrders extends Component
{
   public $vendor;
   public $search = '';
   public $timeframe = 'last_year';
   public $category = '';
   public $pricing = '';
   public $categories;

   public function render()
   {
      $orders = $this->vendor->load(['orders' => function($query) {
         $query->where('orders.status', 'Archived');
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
      }]);
      return view('livewire.vendor-archived-orders', compact('orders'));
   }
}

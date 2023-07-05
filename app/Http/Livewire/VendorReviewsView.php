<?php

namespace App\Http\Livewire;

use Livewire\Component;

class VendorReviewsView extends Component
{
   public $vendor;
   public $search = '';

   public function render()
   {
      $reviews = $this->vendor->load(['reviews' => function($query) {
         $query->when($this->search && $this->search != '', function($query) {
            $query->whereHas('service', function($query) {
               $query->where('service_title', 'LIKE', '%'.$this->search.'%');
            })
            ->orWhereHas('order', function($query) {
               $query->where('order_id', 'LIKE', '%'.$this->search.'%');
            });
         });
      }]);

      return view('livewire.vendor-reviews-view', compact('reviews'));
   }
}

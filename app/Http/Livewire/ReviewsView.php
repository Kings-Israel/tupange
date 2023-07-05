<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Review;

class ReviewsView extends Component
{
   use WithPagination;

   public $service;
   public $perPage = 5;

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
      $reviews = Review::where('service_id', $this->service->id)->paginate($this->perPage);
      return view('livewire.reviews-view', ['reviews' => $reviews]);
   }
}

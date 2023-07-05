<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventsIndex extends Component
{
   use WithPagination;

   public $perPage = 10;
   public $i = 1;
   public $status = "All";
   public $search = '';

   public function paginationView()
   {
      return 'layouts.custom-paginate';
   }

   public function updatingStatus()
   {
      $this->resetPage();
   }

   public function updatingPerPage()
   {
      $this->resetPage();
   }

   public function deleteEvent($id)
   {
      Event::destroy($id);
      $this->reset();
   }

   public function render()
   {
      return view('livewire.events-index', [
         'events' => Event::where('user_id', auth()->user()->id)
               ->when($this->search && $this->search != '', function($query) {
                  return $query->where('event_name', 'like', '%'.$this->search.'%');
               })
               ->when($this->status && $this->status != 'All', function($query) {
                  return $query->where('status', 'like', '%'.$this->status.'%');
               })
               ->orderBy('created_at', "DESC")
               ->paginate($this->perPage)
      ]);
   }
}

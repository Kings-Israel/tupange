<?php

namespace App\Http\Livewire;

use App\Models\EventProgram as ModelsEventProgram;
use App\Models\EventProgramActivity;
use Livewire\Component;
use Livewire\WithPagination;

class EventProgram extends Component
{
   use WithPagination;

   public $i = 1;
   public $perPage;
   public $search;
   public $eventType;

   public function paginationView()
   {
      return 'layouts.custom-paginate';
   }

   public function deleteProgram(ModelsEventProgram $eventProgram)
   {
      $activities = EventProgramActivity::where('event_program_id', $eventProgram->id)->get();
      foreach ($activities as $activity) {
         $activity->delete();
      }

      $eventProgram->delete();
   }

   public function render()
   {
      return view('livewire.event-program', [
         'programs' => ModelsEventProgram::where('user_id', auth()->user()->id)
               ->when($this->search && $this->search != '', function($query) {
                  return $query->where('event_name', 'like', '%'.$this->search.'%');
               })
               ->when($this->eventType && $this->eventType != '', function($query) {
                  return $query->where('event_type', 'like', '%'.$this->eventType.'%');
               })
               ->orderBy('created_at', "DESC")
               ->paginate($this->perPage),
         'event_types' => ModelsEventProgram::where('user_id', auth()->user()->id)->pluck('event_type'),
      ]);
   }
}

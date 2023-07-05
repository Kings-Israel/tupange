<?php

namespace App\Http\Livewire;

use App\Models\EventTask;
use Livewire\Component;
use Livewire\WithPagination;

class EventTasks extends Component
{
   use WithPagination;

   public $event;
   public $categories;
   public $i = 1;

   public function paginationView()
   {
      return 'layouts.custom-paginate';
   }

   public function deleteTask(EventTask $task)
   {
      $task->delete();
      $this->event->refresh();

   }

   public function render()
   {
      return view('livewire.event-tasks', [
         'event' => $this->event,
         'eventUsers' => $this->event->event_users,
         'tasks' => EventTask::where('event_id', $this->event->id)
         ->orderByRaw("FIELD(status , 'Complete', 'Closed') ASC")
            ->paginate($this->event->perPage),
      ]);
   }
}

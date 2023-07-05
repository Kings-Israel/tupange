<?php

namespace App\Http\Livewire;

use App\Models\Gift;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class GiftRegistry extends Component
{
    use WithPagination;

    public $event;
    public $perPage = 10;
    public $i = 1;

    public function paginationView()
    {
        return 'layouts.custom-paginate';
    }

    public function updatingPerPage()
    {
       $this->resetPage();
    }

    public function deleteGift($id)
    {
        $gift = Gift::find($id);

        if($gift->image) {
            Storage::disk('event')->delete('gift/'.$gift->image);
        }

        $gift->delete();

        session()->flash('success', 'Gift removed from registry');
    }

    public function render()
    {
        return view('livewire.gift-registry', [
            'gifts' => Gift::where('event_id', $this->event->id)->paginate($this->perPage),
            'event' => $this->event,
        ]);
    }
}

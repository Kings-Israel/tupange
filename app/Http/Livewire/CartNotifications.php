<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CartNotifications extends Component
{
    public $itemsCount;

    protected $listeners = ['checkCart'];

    public function mount()
    {
        $this->itemsCount = Cart::where('user_id', auth()->user()->id)->count();
    }

    public function checkCart()
    {
        $this->getItemsCount();
    }

    public function getItemsCount()
    {
        if(auth()->guest()){
            session()->flash('error', 'Please login!');

            return;
        }

        $this->itemsCount = Cart::where('user_id', auth()->user()->id)->count();
    }
    public function render()
    {
        return view('livewire.cart-notifications');
    }
}

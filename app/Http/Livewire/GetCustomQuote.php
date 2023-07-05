<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Service;

class GetCustomQuote extends Component
{
    public $service;
    public $isInCart;

    public $listeners = [
       'checkCart'
    ];

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->isInCart = $service->isInCart(auth()->user());
    }

    public function checkCart()
    {
       $this->isInCart = $this->service->isInCart(auth()->user());
    }

    public function addToCart()
    {
        if(auth()->guest()){
            session()->flash('error', 'Please login!');

            return;
        }

        if ($this->isInCart) {
            try {
                $this->service->removeFromCart(auth()->user());
            } catch (ServiceNotFoundInCart $e) {
                //throw $th;
            }
            $this->isInCart = false;
            $this->emit('checkCart');
        } else {
            try {
                $this->service->addServiceToCart(auth()->user());
            } catch (DuplicateServiceException $e) {
                //throw $th;
            }

            $this->isInCart = true;
            $this->emit('checkCart');
        }
    }

    public function render()
    {
        return view('livewire.get-custom-quote');
    }
}

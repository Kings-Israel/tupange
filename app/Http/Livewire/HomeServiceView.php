<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Service;

class HomeServiceView extends Component
{
    public $service;
    public $isInFavorites;
    public $isInCart;

    public function mount(Service $service)
    {
        $this->service = $service->loadCount('reviews');
        $this->isInFavorites = $service->isInUserFavorites(auth()->user());
        $this->isInCart = $service->isInCart(auth()->user());
    }

    public function addToFavorites()
    {
        if(auth()->guest()){
            session()->flash('error', 'Please login!');

            return;
        }

        if ($this->isInFavorites) {
            try {
                $this->service->removeFromFavorites(auth()->user());
            } catch (FavoriteNotFoundException $e) {
                //throw $th;
            }
            $this->isInFavorites = false;
        } else {
            try {
                $this->service->addToFavorites(auth()->user());
            } catch (DuplicateFavoriteException $e) {
                //throw $th;
            }

            $this->isInFavorites = true;
        }

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
        return view('livewire.home-service-view');
    }
}

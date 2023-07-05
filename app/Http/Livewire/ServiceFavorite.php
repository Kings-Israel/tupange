<?php

namespace App\Http\Livewire;

use App\Models\Service;
use Livewire\Component;

class ServiceFavorite extends Component
{
    public $service;
    public $isInFavorites;

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->isInFavorites = $service->isInUserFavorites(auth()->user());
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

    public function render()
    {
        return view('livewire.service-favorite');
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Service;
use Livewire\Component;

class AddToCart extends Component
{
   public $service;

   public function addServiceToCart(Service $service)
   {
      $cart = session()->get('cart');

      if (!$cart) {
         $cart = [
            $service->id => [
               'service_pricing' => null
            ]
         ];

         session()->put('cart', $cart);

         dd(session()->get('cart'));

         return;
      }

      if (isset($cart[$service->id])) {
         dd('Service already in cart');
         return;
      }

      $cart[$service->id] = [
         'service_pricing' => null
      ];

      session()->put('cart', $cart);

      dd(session()->get('cart'));

   }
    public function render()
    {
        return view('livewire.add-to-cart');
    }
}

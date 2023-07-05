<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Event;
use Livewire\Component;
use App\Models\UserRole;
use Livewire\WithPagination;
use App\Models\ServicePricing;

class ClientCartView extends Component
{
   use WithPagination;

   public $item;
   public $servicePricing;
   public $orderMessage;

   public function paginationView()
   {
      return 'layouts.custom-paginate';
   }

   public function removeFromCart(Cart $cart)
   {
      $cart->delete();
      $this->reset();
      $this->emit('checkCart');
      session()->flash('success', "Removed from cart");
   }

   public function render()
   {
      $items = Cart::where('user_id', auth()->user()->id)->paginate(10);
      $items->load('service');
      $items->each(fn ($item) => $item->service->load('service_pricing'));
      $events = auth()->user()->events;

      // Check if user has been assigned roles
      if(auth()->user()->hasAssignedRoles()) {
         $roles = UserRole::where('user_id', auth()->id())->get();

         foreach ($roles as $role) {
            // Check if user role is 'Committee'
            if($role->role_id == 1) {
               $events->push(Event::find($role->event_id));
            }
         }
      }

      return view('livewire.client-cart-view')->with(['items' => $items, 'events' => $events]);
   }
}

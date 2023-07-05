<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Service;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\ServicePricing;
use App\Exceptions\ServiceNotFoundInCart;
use App\Exceptions\DuplicateServiceException;

class PricingsIndex extends Component
{
   public $service;
   public $isInCart;

   public $listeners = [
      'checkCart'
   ];

   public function mount(Service $service)
   {
      $this->service = $service;
   }

   public function deletePricing(Request $request, $id)
   {
      $orders_with_pricing = Order::where('service_pricing_id', $id)->whereIn('status', ['Received', 'Paid', 'Delivered', 'Completed'])->get();
      if (!$orders_with_pricing) {
         $orders = Order::where('service_pricing_id', $id)->get();
         $orders->each(fn ($order) => $order->update(['service_pricing_id', NULL]));

         ServicePricing::destroy($id);

         $this->service->refresh();
      } else {
         session()->flash('message', 'Cannot delete pricing with active orders');
      }
   }

   public function checkCart()
   {
      $this->isInCart = $this->service->isInCart(auth()->user());
   }

   public function addToCart(ServicePricing $servicePricing)
   {
      if(auth()->guest()){
         session()->flash('error', 'Please login!');

         return;
     }

     if ($this->service->pricingIsInCart(auth()->user(), $servicePricing)) {
         try {
             $this->service->removePricingFromCart(auth()->user(), $servicePricing);
             $this->service->refresh();
         } catch (ServiceNotFoundInCart $e) {
             //throw $th;
         }
         $this->emit('checkCart');
     } else {
         try {
             $this->service->addServicePricingToCart(auth()->user(), $servicePricing);
             $this->service->refresh();
         } catch (DuplicateServiceException $e) {
             //throw $th;
         }

         $this->emit('checkCart');
     }
   }

   public function render()
   {
      return view('livewire.pricings-index');
   }
}

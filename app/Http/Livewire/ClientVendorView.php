<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ClientVendorView extends Component
{
   public $vendor;

   public function render()
   {
      return view('livewire.client-vendor-view');
   }
}

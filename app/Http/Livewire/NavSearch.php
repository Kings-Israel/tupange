<?php

namespace App\Http\Livewire;

use App\Models\BudgetTransaction;
use App\Models\Category;
use App\Models\Service;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class NavSearch extends Component
{
   public $nav_search = '';

   public function render()
   {
      $results = collect([]);
      if (strlen($this->nav_search) >= 2) {
         $results = (new Search())
               ->registerModel(Vendor::class, 'company_name')
               ->registerModel(Service::class, function(ModelSearchAspect $modelSearchAspect) {
                  $modelSearchAspect
                  ->addSearchableAttribute('service_title')
                  ->where('service_status_id', '1');
               })
               ->search($this->nav_search);
      }
      return view('livewire.nav-search', ['results' => collect($results)->shuffle()->take(7)]);
   }
}

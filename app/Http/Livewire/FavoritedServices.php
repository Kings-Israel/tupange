<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Enums\Counties;
use App\Models\Service;
use Livewire\Component;
use App\Models\Category;
use App\Models\Favorites;
use Livewire\WithPagination;

class FavoritedServices extends Component
{
   use WithPagination;

   public $perPage = 24;
   public $search = "";
   public $category = "All";
   public $categoryFilter = [];
   public $selectedCategories = [];
   public $date = '';
   public $year = '';
   public $location = "";
   public $service_categories;
   public $event_type = '';

   public function mount()
   {
      $this->service_categories = Category::all();
   }

   public function updatingCategory()
   {
      $this->resetPage();
   }

   public function updatingLocation()
   {
      $this->resetPage();
   }

   public function updatingSearch()
   {
      $this->resetPage();
   }

   public function updatedCategory($name)
   {
      if ($name === "All") {
         $this->categoryFilter = [];
         $this->selectedCategories = [];
         return;
      }

      $category = $this->service_categories->where('name', $name)->first();

      if (collect($this->categoryFilter)->contains($category->id)) {
         collect($this->categoryFilter)->forget($category->id);
         return;
      }

      array_push($this->categoryFilter, $category->id);
      array_push($this->selectedCategories, $category->name);
   }

   public function removeCategory($key)
   {
      $value = $this->selectedCategories[$key];
      $category = $this->service_categories->where('name', $value)->first();
      if (($key = array_search($category->id, $this->categoryFilter)) !== false) {
         unset($this->categoryFilter[$key]);
      }

      if (($key = array_search($category->name, $this->selectedCategories)) !== false) {
         unset($this->selectedCategories[$key]);
      }
   }

   public function clearAllFilters()
   {
      $this->category = '';
      $this->search = '';
      $this->date = '';
      $this->year = '';
      $this->location = '';
      $this->categoryFilter = [];
      $this->selectedCategories = [];
      $this->event_type = '';
      $this->resetPage();
   }

   public function removeDateFilter()
   {
      $this->date = '';
   }

   public function removeYearFilter()
   {
      $this->date = '';
      $this->year = '';
   }

   public function paginationView()
   {
      return 'layouts.custom-paginate';
   }

   public function render()
   {
      $user_favorited_services = Favorites::where('user_id', auth()->id())->get()->pluck('service_id');

      $services = Service::
         where('service_status_id', 1)
         ->whereIn('id', $user_favorited_services)
         ->when($this->date && $this->date != '', function($query) {
            $this->date = Carbon::parse($this->date)->format('Y-m-d');
               return $query->where('pause_from', null)->orWhere('pause_from', '>', $this->date);
         })
         ->when($this->date && $this->date != '', function($query) {
            $this->date = Carbon::parse($this->date)->format('Y-m-d');
               return $query->where('pause_until', null)->orWhere('pause_until', '<', $this->date);
         })
         ->when($this->categoryFilter && ! collect($this->categoryFilter)->isEmpty(), function($query) {
            return $query->whereIn('category_id', $this->categoryFilter);
         })
         ->when(strlen($this->search) >= 3, function($query) {
            return $query->where('service_title', 'like', '%'.$this->search.'%');
         })
         ->when($this->location && $this->location != 'All', function($query) {
            return $query->where('service_location', 'like', '%'.strtolower($this->location).'%');
         })
         ->when($this->event_type && $this->event_type != '', function($query) {
            return $query->where('service_description', 'like', '%'.$this->event_type.'%')->orWhere('service_title', 'like', '%'.$this->event_type.'%');
         })
         ->with('reviews')
         ->orderBy('service_rating', 'DESC')
         ->paginate($this->perPage);

      return view('livewire.favorited-services', [
         "services" => $services,
         'categories' => $this->service_categories,
         'counties' => Counties::counties()
      ]);
   }
}

<?php

namespace App\Http\Livewire;

use App\Enums\Counties;
use App\Models\Category;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ClientServicesView extends Component
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

   public function mount(?Request $request)
   {
      $this->service_categories = Category::all();

      if ($request) {
         if ($request->method() == "GET") {
            if ($request->category == null) {
               return;
            }
            $this->updatedCategory($request->category);
         }
         if ($request->has('category') && $request->category != null) {
            $categories = explode(', ', $request->category);
            collect($categories)->each(fn ($category) => $this->updatedCategory($category));
         }
         if ($request->has('when_date') && $request->when_date != null) {
            $month = Carbon::parse($request->when_date)->format('m');
            $day = Carbon::parse($request->when_date)->format('d');
            $year = Carbon::parse($request->when_date)->format('Y');
            $this->date = $month.'/'.$day.'/'.$year;
         }

         if ($request->has('when_month') && $request->when_month != null) {
            $month = Carbon::parse($request->when_month)->format('m');
            $day = Carbon::parse($request->when_month)->format('d');
            $year = Carbon::parse($request->when_month)->format('Y');
            $this->date = $month.'/'.$day.'/'.$year;
            $this->year = $month.'/'.$year;
         }

         if ($request->has('event_type')) {
            if ($request->event_type == 'Other') {
               $this->event_type = $request->custom_event_type;
            } else {
               $this->event_type = $request->event_type;
            }
         }
      }
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
      return view('livewire.client-services-view', [
         "services" => Service::
         where('service_status_id', 1)
         ->when($this->date && $this->date != '', function($query) {
            $this->date = Carbon::parse($this->date)->format('Y-m-d');
               return $query->where('pause_from', null)->orWhere('pause_from', '>', $this->date);
         })
         ->when($this->date && $this->date != '', function($query) {
            $this->date = Carbon::parse($this->date)->format('Y-m-d');
               return $query->where('pause_until', null)->orWhere('pause_until', '<', $this->date);
         })
         // ->when($this->category && $this->category != 'All', function($query) {
         //    return $query->where('category_id', $this->service_categories->pluck('id', 'name')->get($this->category));
         // })
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
         ->with('favorites', 'reviews')
         ->orderBy('service_rating', 'DESC')
         ->paginate($this->perPage),
         'categories' => $this->service_categories,
         'counties' => Counties::counties(),
      ]);
   }
}

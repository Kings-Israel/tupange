<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Service;
use App\Models\ServicePricing;
use App\Models\ServiceStatus;
use Livewire\Component;
use Livewire\WithPagination;

class VendorServices extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $status = "All";
    public $search = "";
    public $category = "";
    public $i = 1;

    public function paginationView()
    {
        return 'layouts.custom-paginate';
    }

    public function updatingStatus()
    {
       $this->resetPage();
    }

    public function updatingPerPage()
    {
       $this->resetPage();
    }

    public function updatingSearch()
    {
       $this->resetPage();
    }

    public function updatingCategory()
    {
       $this->resetPage();
    }

    public function pauseService(Service $service)
    {
        $service->service_status_id = 2;
        $service->save();
    }

    public function deleteService(Service $service)
    {
        $service->service_status_id = 3;
        $service->save();
    }

    public function restoreService(Service $service)
    {
       $vendor = $service->vendor;
       if ($vendor->status == 'Suspended') {
         return back()->with('error', 'Your vendor account has been suspended. You cannot proceed with this action.');
       }
        $service->service_status_id = 1;
        if ($service->pause_from != null) {
           $service->pause_from = null;
        }
        if ($service->pause_until != null) {
            $service->pause_until = null;
         }
        $service->save();
    }

    public function render()
    {
        $services_statuses = ServiceStatus::all();
        $service_categories = Category::all();
        $vendor = auth()->user()->vendor;

        return view('livewire.vendor-services', [
            "services" => Service::
            where('vendor_id', $vendor->id)
            ->when($this->status && $this->status != 'All', function($query) use ($services_statuses) {
                return $query->where('service_status_id', $services_statuses->pluck('id', 'name')->get($this->status));
            })
            ->when($this->category && $this->category != 'All', function($query) use ($service_categories) {
                return $query->where('category_id', $service_categories->pluck('id', 'name')->get($this->category));
            })
            ->when(strlen($this->search) >= 3, function($query) {
                return $query->where('service_title', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'DESC')
            // Move deleted services to bottom of the list
            ->orderByRaw("FIELD(service_status_id , 3) ASC")
            ->paginate($this->perPage),
            'categories' => $service_categories,
            'statuses' => $services_statuses,
            'vendor' => $vendor,
        ]);
    }
}

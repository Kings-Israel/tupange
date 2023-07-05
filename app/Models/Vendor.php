<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Support\Facades\Http;

class Vendor extends Model implements Searchable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $attribites = [
       'status' => 'Pending'
    ];

    public function getSearchResult(): SearchResult
     {
        $url = route('vendor.view', $this->id);

         return new \Spatie\Searchable\SearchResult(
            $this,
            $this->company_name,
            $url
         );
     }
    /**
     * Get the user that owns the Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the services for the Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get all of the orders for the Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function orders()
    {
        return $this->hasManyThrough(Order::class, Service::class);
    }

    /**
     * Get all of the reviews for the Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Service::class);
    }

    public function getCompanyLogo(string $company_logo)
    {
        return Storage::disk('vendor')->url('logo/'.$company_logo);
    }

    public function getVendorLocation($service_location)
    {
      $location = explode(',', $service_location);

      substr($location[1], 0, -1);

      $lat = (double) ltrim($location[0], $location[0][0]);
      $long = (double) ltrim($location[1], $location[1][0]);

      $service_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&key=AIzaSyCisnVFSnc5QVfU2Jm2W3oRLqMDrKwOEoM');
      $service_location = json_decode($service_location->body());
      return $service_location->results[6]->formatted_address;
    }

    public function getVendorRating()
    {
      $services = $this->services;
      $servicesCount = $services->count();
      $servicesRating = $services->pluck('service_rating')->sum();
      $vendorRating = $servicesRating / $servicesCount;
      return $vendorRating;
    }

    public function hasPausedAllServices()
    {
      if ($this->services->where('service_status_id', 1)->count() <= 0) {
         return true;
      }

      return false;
    }
}

<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ServiceNotFoundInCart;
use App\Exceptions\DuplicateServiceException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicePricing extends Model
{
   use HasFactory;

   protected $guarded = [];

   protected $casts = [
      'service_packages' => 'array',
   ];

   /**
    * Get the service that owns the ServicePricing
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function service()
   {
      return $this->belongsTo(Service::class);
   }

   /**
    * Get all of the orders for the ServicePricing
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function orders()
   {
      return $this->hasMany(Order::class);
   }

   public function hasActiveOrders()
   {
      $orders_with_pricing = $this->orders()->where('service_pricing_id', $this->id)->whereIn('status', ['Received', 'Paid', 'Delivered', 'Completed'])->count();
      if ($orders_with_pricing > 0) {
         return true;
      }
      return false;
   }

   public function activeOrdersCount()
   {
      return $this->orders()->where('service_pricing_id', $this->id)->whereIn('status', ['Received', 'Paid', 'Delivered', 'Completed'])->count();
   }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorNotification extends Model
{
   use HasFactory;

   protected $guarded = [];

   /**
    * Get the user that owns the VendorNotification
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function user()
   {
      return $this->belongsTo(User::class);
   }

   /**
    * Get the service that owns the VendorNotification
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function order()
   {
      return $this->belongsTo(Order::class);
   }
}

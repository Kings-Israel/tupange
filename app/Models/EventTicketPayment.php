<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicketPayment extends Model
{
   use HasFactory;

   /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
   protected $guarded = [];

   /**
    * Get the eventGuestDetail that owns the EventTicketPayment
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function eventGuestDetail()
   {
      return $this->belongsTo(EventGuestDetail::class);
   }
}

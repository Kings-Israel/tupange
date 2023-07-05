<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGuestDetail extends Model
{
   use HasFactory;

   protected $guarded = [];

   protected $attributes = [
      'status' => 'Default',
      'type' => 'General Admission'
   ];

   protected $casts = [
      'extend_invitation' => 'boolean',
      'ticketSent' => 'boolean'
   ];

   /**
    * Get the event that owns the EventGuestDetail
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function event()
   {
      return $this->belongsTo(Event::class);
   }

   /**
    * Get all of the payments for the EventGuestDetail
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function payments()
   {
      return $this->hasMany(EventTicketPayment::class);
   }
}

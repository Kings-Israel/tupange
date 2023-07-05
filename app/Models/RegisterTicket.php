<?php

namespace App\Models;

use App\Models\Event;
use App\Models\EventGuestDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegisterTicket extends Model
{
   use HasFactory;

   protected $guarded = [];

   /**
    * Get the event that owns the RegisterTicket
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function event()
   {
      return $this->belongsTo(Event::class);
   }

   public function getInvitedGuestsCount()
   {
      return EventGuestDetail::where('ticket_title', $this->title)->where('ticket_price', $this->price)->count();
   }
}

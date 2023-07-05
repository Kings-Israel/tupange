<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventUser extends Model
{
   use HasFactory;

   protected $guarded = [];

   protected $casts = [
      'isSent' => 'bool'
   ];

   /**
    * Get the event that owns the EventUser
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function event()
   {
       return $this->belongsTo(Event::class);
   }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
   use HasFactory;

   protected $guarded = [];

   /**
    * Get the event that owns the Register
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function event()
   {
       return $this->belongsTo(Event::class);
   }
}

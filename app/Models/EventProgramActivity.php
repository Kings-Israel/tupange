<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventProgramActivity extends Model
{
   use HasFactory;

   protected $guarded = [];

   protected $casts = [
      'start_time' => 'datetime',
      'end_time' => 'datetime'
   ];

   /**
    * Get the eventProgram that owns the EventProgramActvity
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function eventProgram()
   {
       return $this->belongsTo(EventProgram::class);
   }
}

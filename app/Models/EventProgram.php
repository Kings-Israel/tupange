<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventProgram extends Model
{
   use HasFactory;

   protected $guarded = [];

   protected $casts = [
      'start_date' => 'datetime',
      'end_date' => 'datetime',
      'contacts' => Json::class,
      'canDownload' => 'bool'
   ];

   /**
    * Get the user that owns the EventProgram
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function user()
   {
       return $this->belongsTo(User::class);
   }

   /**
    * Get the event that owns the EventProgram
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function event()
   {
      return $this->belongsTo(Event::class);
   }

   /**
    * Get all of the eventProgramActivities for the EventProgram
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function eventProgramActivities()
   {
      return $this->hasMany(EventProgramActivity::class);
   }
}

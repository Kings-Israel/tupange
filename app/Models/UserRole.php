<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
   use HasFactory;

   protected $guarded = [];

   /**
    * Get the user that owns the UserRole
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function user()
   {
      return $this->belongsTo(User::class);
   }

   /**
    * Get the event that owns the UserRole
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function event()
   {
      return $this->belongsTo(Event::class);
   }

   /**
    * Get the role that owns the UserRole
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function role()
   {
       return $this->belongsTo(Role::class);
   }
}

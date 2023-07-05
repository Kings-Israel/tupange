<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   use HasFactory;

   protected $guarded = [];

   /**
    * Get all of the user_roles for the Role
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function user_roles()
   {
       return $this->hasMany(UserRole::class);
   }
}

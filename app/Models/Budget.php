<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
   use HasFactory;

   protected $guarded = [];

   /**
    * Get the event that owns the Budget
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function event()
   {
      return $this->belongsTo(Event::class);
   }

   /**
    * Get all of the budgetTransactions for the Budget
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function budgetTransactions()
   {
       return $this->hasMany(BudgetTransaction::class);
   }
}

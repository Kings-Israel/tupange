<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetTransaction extends Model
{
   use HasFactory;

   protected $guarded = [];

   /**
    * Get the budget that owns the BudgetTransaction
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function budget()
   {
      return $this->belongsTo(Budget::class);
   }
}

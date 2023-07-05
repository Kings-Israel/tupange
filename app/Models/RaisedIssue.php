<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaisedIssue extends Model
{
   use HasFactory;

   protected $guarded = [];

   /**
    * Get the user that owns the RaisedIssue
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function user()
   {
      return $this->belongsTo(User::class);
   }

   /**
    * Get all of the raisedIssueResponses for the RaisedIssue
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function raisedIssueResponses()
   {
       return $this->hasMany(RaisedIssueResponse::class);
   }
}

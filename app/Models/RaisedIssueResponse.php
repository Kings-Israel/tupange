<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaisedIssueResponse extends Model
{
   use HasFactory;

   protected $guarded = [];

   protected $casts = [
      'isAdminResponse' => 'bool'
   ];

   /**
    * Get the user that owns the RaisedIssueResponse
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function user()
   {
      return $this->belongsTo(User::class);
   }

   /**
    * Get the raisedIssue that owns the RaisedIssueResponse
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function raisedIssue()
   {
      return $this->belongsTo(RaisedIssue::class);
   }
}

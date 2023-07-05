<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
   protected $table = 'messages';

   protected $casts = [
      'is_read' => "bool"
   ];

   /**
    * Get the order that owns the Messages
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function order()
   {
      return $this->belongsTo(Order::class, 'order_id', 'order_id');
   }

   public function hasUnreadMessages()
   {
      $messages = Messages::where('order_id', $this->order_id)->where('sent_to', auth()->user()->id)->where('is_read', false)->count();
      if ($messages > 0) {
         return true;
      }

      return false;
   }
}

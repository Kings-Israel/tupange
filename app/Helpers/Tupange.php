<?php
namespace App\Helpers;
use App\Models\Review;
use App\Models\User;
class Tupange
{
   //order reviews
   public static function service_reviews($id){
      $reviews = Review::join('users','users.id','=','reviews.user_id')
                     ->where('service_id',$id)
                     ->orderby('reviews.id','desc')
                     ->select('*','reviews.created_at as created_at')
                     ->get();
      return $reviews;
   }

   //user details
   public static function user($id){
      $user = User::find($id);
      return $user;
   }
}

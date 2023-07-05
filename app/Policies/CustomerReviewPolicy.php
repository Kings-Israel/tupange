<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerReviewPolicy
{
   public function create(User $user)
   {
      if ($user->orders()->count() > 5) {
         return true;
      }
   }
}

<?php

namespace App\Policies;

use App\Models\BudgetTransaction;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BudgetTransactionPolicy
{
   use HandlesAuthorization;

   /**
    * Determine whether the user can view any models.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function viewAny(User $user)
   {
      //
   }

   /**
    * Determine whether the user can view the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\BudgetTransaction  $budgetTransaction
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function view(User $user, BudgetTransaction $budgetTransaction)
   {
      if ($budgetTransaction->reference == NULL) {
         return false;
      }

      $eventTransaction = Payment::where('transaction_id', $budgetTransaction->reference)->first();

      if ($eventTransaction) {
         return true;
      }
   }

   /**
    * Determine whether the user can create models.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function create(User $user)
   {
      //
   }

   /**
    * Determine whether the user can update the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\BudgetTransaction  $budgetTransaction
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function update(User $user, BudgetTransaction $budgetTransaction)
   {
      if ($budgetTransaction->reference == NULL) {
         return true;
      }

      $eventTransaction = Payment::where('transaction_id', $budgetTransaction->reference)->first();

      if ($eventTransaction) {
         return false;
      }

   return true;
   }

   /**
    * Determine whether the user can delete the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\BudgetTransaction  $budgetTransaction
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function delete(User $user, BudgetTransaction $budgetTransaction)
   {
      //
   }

   /**
    * Determine whether the user can restore the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\BudgetTransaction  $budgetTransaction
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function restore(User $user, BudgetTransaction $budgetTransaction)
   {
      //
   }

   /**
    * Determine whether the user can permanently delete the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\BudgetTransaction  $budgetTransaction
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function forceDelete(User $user, BudgetTransaction $budgetTransaction)
   {
      //
   }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BudgetTransaction;
use App\Models\Category;
use Livewire\WithPagination;

class BudgetTransactions extends Component
{
   use WithPagination;

   public $event;
   public $budget;
   public $title = '';
   public $type = "All";
   public $transaction_service_category = "All";

   public function paginationView()
   {
      return 'layouts.custom-paginate';
   }

   public function render()
   {
      $categories = Category::all()->pluck('name');
      $budgetTransactions = BudgetTransaction::
         where(['event_id' => $this->event->id, 'budget_id' => $this->budget->id])
         ->when($this->type && $this->type != 'All', function($query) {
            return $query->where('type', $this->type);
         })
         ->when(strlen($this->title) >= 3, function($query) {
            return $query->where('title', 'like', '%'.$this->title.'%');
         })
         ->when($this->transaction_service_category && $this->transaction_service_category != 'All', function($query) {
            return $query->where('transaction_service_category', $this->transaction_service_category);
         })
         ->paginate(10);
      $budgetTransactions->budget = BudgetTransaction::where(['event_id' => $this->event->id, 'budget_id' => $this->budget->id, 'type' => 'Top Up'])->sum('amount');
      $budgetTransactions->spent = BudgetTransaction::where(['event_id' => $this->event->id, 'budget_id' => $this->budget->id, 'type' => 'Expense'])->sum('amount');
      $budgetTransactions->balance = $budgetTransactions->budget - $budgetTransactions->spent;
      return view('livewire.budget-transactions', compact('budgetTransactions', 'categories'));
   }
}

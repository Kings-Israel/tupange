<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionCategoryToBudgetTransactionsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::table('budget_transactions', function (Blueprint $table) {
         $table->string('transaction_service_category')->nullable();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::table('budget_transactions', function (Blueprint $table) {
         $table->dropColumn('transaction_service_category');
      });
   }
}

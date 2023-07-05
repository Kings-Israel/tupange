<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesapalPaymentsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('pesapal_payments', function (Blueprint $table) {
         $table->id();
         $table->string('transaction_id')->nullable();
         $table->string('tracking_id')->nullable();
         $table->string('amount')->nullable();
         $table->string('account_number')->nullable();
         $table->foreignid('payable_id');
         $table->string('payable_type');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('pesapal_payments');
   }
}

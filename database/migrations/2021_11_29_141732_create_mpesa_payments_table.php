<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpesaPaymentsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('mpesa_payments', function (Blueprint $table) {
         $table->id();
         $table->foreignId('order_id');
         $table->string('account');
         $table->string('checkout_request_id');
         $table->bigInteger('phone');
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
      Schema::dropIfExists('mpesa_payments');
   }
}

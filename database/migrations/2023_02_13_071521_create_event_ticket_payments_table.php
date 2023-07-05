<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTicketPaymentsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('event_ticket_payments', function (Blueprint $table) {
         $table->id();
         $table->foreignId('event_guest_detail_id')->references('id')->on('event_guest_details')->onDelete('cascade')->onUpdate('cascade');
         $table->string('amount');
         $table->string('payment_method')->nullable();
         $table->string('transaction_id')->nullable();
         $table->string('mpesa_checkout_request_id')->nullable();
         $table->string('paid_by_name')->nullable();
         $table->string('paid_by_email')->nullable();
         $table->string('paid_by_phone_number')->nullable();
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
      Schema::dropIfExists('event_ticket_payments');
   }
}

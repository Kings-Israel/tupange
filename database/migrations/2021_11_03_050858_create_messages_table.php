<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('messages', function (Blueprint $table) {
         $table->id();
         $table->char('order_id')->nullable();
         $table->text('message')->nullable();
         $table->char('attachment')->nullable();
         $table->char('sent_to')->nullable();
         $table->char('sent_from')->nullable();
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
      Schema::dropIfExists('messages');
   }
}

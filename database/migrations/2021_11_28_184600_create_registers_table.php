<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistersTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('registers', function (Blueprint $table) {
         $table->id();
         $table->string('barcode');
         $table->string('names');
         $table->bigInteger('phone_number')->nullable();
         $table->text('address')->nullable();
         $table->text('company')->nullable();
         $table->string('ticket_title');
         $table->integer('ticket_price')->default(0);
         $table->integer('guests')->default(0);
         $table->bigInteger('amount');
         $table->text('notes')->nullable();
         $table->boolean('sent')->default(false);
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
      Schema::dropIfExists('registers');
   }
}

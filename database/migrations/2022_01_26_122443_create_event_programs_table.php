<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventProgramsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('event_programs', function (Blueprint $table) {
         $table->id();
         $table->foreignId('event_id')->nullable();
         $table->foreignId('user_id')->nullable();
         $table->string('event_type');
         $table->string('event_name');
         $table->dateTime('start_date');
         $table->dateTime('end_date');
         $table->text('venue_location_lat');
         $table->text('venue_location_long');
         $table->text('venue_location');
         $table->text('direction_description');
         $table->json('contacts')->nullable();
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
      Schema::dropIfExists('event_programs');
   }
}

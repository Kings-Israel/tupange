<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegistrationDetailsToGuestDetailsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::table('event_guest_details', function (Blueprint $table) {
         $table->string('ticket_title')->nullable();
         $table->string('ticket_price')->nullable();
         $table->string('notes')->nullable();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::table('event_guest_details', function (Blueprint $table) {
         $table->dropColumn('ticket_title');
         $table->dropColumn('ticket_price');
         $table->dropColumn('notes');
      });
   }
}

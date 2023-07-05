<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketAttendanceDetailsAndPaymentDetailsToEventGuestDetailsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::table('event_guest_details', function (Blueprint $table) {
         $table->bigInteger('guests_attended')->nullable()->default(0);
         $table->boolean('is_paid')->nullable()->default(false);
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
         $table->dropColumn('guests_attended');
         $table->dropColumn('is_paid');
      });
   }
}

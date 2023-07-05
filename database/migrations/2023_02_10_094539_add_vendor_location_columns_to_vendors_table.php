<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorLocationColumnsToVendorsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::table('vendors', function (Blueprint $table) {
         $table->string('location_lat')->nullable();
         $table->string('location_long')->nullable();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::table('vendors', function (Blueprint $table) {
         $table->dropColumn('location_lat');
         $table->dropColumn('location_long');
      });
   }
}

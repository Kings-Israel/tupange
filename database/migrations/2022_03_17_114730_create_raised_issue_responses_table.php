<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaisedIssueResponsesTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('raised_issue_responses', function (Blueprint $table) {
         $table->id();
         $table->foreignId('user_id')->nullable();
         $table->foreignId('admin_id')->nullable();
         $table->foreignId('raised_issue_id');
         $table->text('response');
         $table->boolean('isAdminResponse')->default(false);
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
      Schema::dropIfExists('raised_issue_responses');
   }
}

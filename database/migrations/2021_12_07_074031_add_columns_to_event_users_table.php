<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEventUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_users', function (Blueprint $table) {
         $table->string('names');
         $table->string('email');
         $table->foreignId('event_id');
         $table->foreignId('user_id')->nullable();
         $table->enum('role', ['Committee', 'Tasks']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_users', function (Blueprint $table) {
            //
        });
    }
}

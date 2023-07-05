<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('event_name');
            $table->string('event_type');
            $table->double('event_location_lat');
            $table->double('event_location_long');
            $table->string('event_location')->nullable();
            $table->dateTime('event_start_date');
            $table->dateTime('event_end_date');
            $table->string('event_description');
            $table->string('event_poster')->nullable();
            $table->enum('status', ['Past', 'Live', 'Active']);
            $table->json('event_settings')->nullable();
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
        Schema::dropIfExists('events');
    }
}

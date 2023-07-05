<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventGuestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_guest_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->string('barcode');
            $table->enum('status', ['Default', 'Invited', 'Confirmed', 'Attended']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('type');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->string('address')->nullable();
            $table->text('diet_instructions')->nullable();
            $table->integer('table_number')->nullable();
            $table->boolean('extend_invitation')->default(false);
            $table->bigInteger('invited_guests_number')->nullable();
            $table->string('invitation_phone_number')->nullable();
            $table->string('invitation_email')->nullable();
            $table->text('custom_question')->nullable();
            $table->text('custom_answer')->nullable();
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
        Schema::dropIfExists('event_guest_details');
    }
}

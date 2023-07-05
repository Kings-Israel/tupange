<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->bigInteger('value')->nullable();
            $table->date('received_date')->nullable();
            $table->string('received_by')->nullable();
            $table->string('received_from')->nullable();
            $table->bigInteger('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('image')->nullable();
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
        Schema::dropIfExists('gifts');
    }
}

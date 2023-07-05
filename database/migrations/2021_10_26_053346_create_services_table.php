<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id');
            $table->foreignId('category_id');
            $table->foreignId('service_status_id')->default(1);
            $table->string('service_title');
            $table->text('service_description');
            $table->string('service_contact_email');
            $table->bigInteger('service_contact_phone_number');
            $table->string('service_image');
            $table->double('service_location_lat')->nullable();
            $table->double('service_location_long')->nullable();
            $table->text('service_location')->nullable();
            $table->text('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}

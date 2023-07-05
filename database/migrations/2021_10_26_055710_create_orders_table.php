<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('service_id');
            $table->foreignId('event_id')->nullable();
            $table->foreignId('service_pricing_id')->nullable();
            $table->string('message')->nullable();
            $table->enum('status', ['Received', 'Paid', 'Delivered', 'Completed', 'Disputed', 'Archived', 'Sent', 'Declined', 'Cancelled', 'Removed']);
            $table->string('status_reason')->nullable();
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
        Schema::dropIfExists('orders');
    }
}

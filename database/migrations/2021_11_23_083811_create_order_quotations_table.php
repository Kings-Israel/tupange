<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->string('order_pricing_title');
            $table->bigInteger('order_pricing_price');
            $table->text('order_pricing_agreement')->nullable();
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
        Schema::dropIfExists('order_quotations');
    }
}

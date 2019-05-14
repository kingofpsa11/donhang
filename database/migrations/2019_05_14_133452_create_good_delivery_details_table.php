<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_delivery_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('output_order_detail_id');
            $table->foreign('output_order_detail_id')->references('id')->on('output_order_details')->onDelete('cascade');
            $table->unsignedBigInteger('good_delivery_id');
            $table->foreign('good_delivery_id')->references('id')->on('good_deliveries')->onDelete('cascade');
            $table->integer('quantity');
            $table->unsignedBigInteger('store');
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
        Schema::dropIfExists('good_delivery_details');
    }
}

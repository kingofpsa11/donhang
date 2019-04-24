<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->unsignedBigInteger('price_id');
            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
            $table->integer('quantity');
            $table->unsignedBigInteger('selling_price')->nullable();
            $table->unsignedBigInteger('manufacturer_order_id');
            $table->foreign('manufacturer_order_id')->references('id')->on('manufacturers')->onDelete('cascade');
//            $table->string('manufacturer_order_number')->nullable();
            $table->date('deadline');
            $table->text('note')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('contract_details');
    }
}

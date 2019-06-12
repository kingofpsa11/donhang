<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodReceiveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_receive_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('good_receive_id');
            $table->foreign('good_receive_id')->references('id')->on('good_receives')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->foreign('bom_id')->references('id')->on('boms')->onDelete('cascade');
            $table->float('quantity', 8 , 3);
            $table->float('actual_quantity', 8 , 3)->default(0);
            $table->unsignedBigInteger('store_id');
//            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
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
        Schema::dropIfExists('good_receive_details');
    }
}

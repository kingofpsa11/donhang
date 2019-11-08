<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoleWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pole_weights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->float('area', 8 , 2);
            $table->float('weight', 8, 2);
            $table->float('ty_le_nhan_cong', 8 , 2);
            $table->unsignedBigInteger('expense_of_pole_id');
            $table->foreign('expense_of_pole_id')->references('id')->on('expense_of_poles')->onDelete('cascade');
            $table->integer('don_gia');
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
        Schema::dropIfExists('pole_weights');
    }
}

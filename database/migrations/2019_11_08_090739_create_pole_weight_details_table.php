<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoleWeightDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pole_weight_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pole_weight_id');
            $table->foreign('pole_weight_id')->references('id')->on('pole_weights')->onDelete('cascade');
            $table->string('name', 100)->nullable();
            $table->integer('shape');
            $table->integer('quantity');
            $table->float('d_ngon', 8 , 2)->nullable();
            $table->float('d_goc', 8 , 2)->nullable();
            $table->float('day', 8 , 2);
            $table->integer('chieu_cao')->nullable();
            $table->float('chieu_dai', 8 ,2)->nullable();
            $table->float('chieu_rong', 8 ,2)->nullable();
            $table->float('dien_tich', 8,2)->nullable();
            $table->float('khoi_luong', 8,2)->nullable();
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
        Schema::dropIfExists('pole_weight_details');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStepNoteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('step_note_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('step_note_id');
            $table->morphs('stepable');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('step_note_id')->references('id')->on('step_notes')->onDelete('cascade');
            $table->unsignedBigInteger('contract_detail_id');
            $table->foreign('contract_detail_id')->references('id')->on('contract_details')->onDelete('cascade');
            $table->float('quantity',8,3);
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
        Schema::dropIfExists('step_note_details');
    }
}

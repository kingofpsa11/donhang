<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManufacturerNoteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturer_note_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('manufacturer_note_id');
            $table->foreign('manufacturer_note_id')->references('id')->on('manufacturer_notes')->onDelete('cascade');
            $table->unsignedBigInteger('contract_detail_id');
            $table->foreign('contract_detail_id')->references('id')->on('contract_details')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('bom_detail_id');
            $table->foreign('bom_detail_id')->references('id')->on('bom_details')->onDelete('cascade');
            $table->integer('length');
            $table->float('thickness', 8, 2);
            $table->integer('top_perimeter');
            $table->integer('bottom_perimeter');
            $table->integer('quantity');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('manufacturer_note_details');
    }
}

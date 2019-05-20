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
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->foreign('bom_id')->references('id')->on('boms')->onDelete('cascade');
            $table->integer('quantity');
            $table->text('note');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseOfPolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_of_poles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('gia_thep');
            $table->string('name');
            $table->float('du_phong_vat_tu', 8, 5);
            $table->float('vat_tu_phu', 8, 5);
            $table->float('hao_phi', 8, 5);
            $table->integer('nhan_cong_truc_tiep');
            $table->float('chi_phi_chung', 8, 5);
            $table->integer('chi_phi_ma_kem');
            $table->integer('chi_phi_van_chuyen');
            $table->float('lai', 8, 5);
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
        Schema::dropIfExists('expense_of_poles');
    }
}

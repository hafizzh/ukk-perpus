<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDdcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ddc', function (Blueprint $table) {
            $table->id('id_ddc');
            $table->foreignId('id_rak');
            $table->string('kode_ddc', 10)->unique();
            $table->string('ddc', 100)->unique();
            $table->string('keterangan', 100)->nullable();
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
        Schema::dropIfExists('tbl_ddc');
    }
}


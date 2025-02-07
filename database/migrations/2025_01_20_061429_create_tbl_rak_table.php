<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_rak', function (Blueprint $table) {
            $table->id('id_rak'); // ini adalah unsignedBigInteger secara default
            $table->string('kode_rak', 10)->unique();
            $table->string('rak', 25)->unique();
            $table->string('keterangan', 50)->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_rak');
    }
};

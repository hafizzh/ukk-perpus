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
        Schema::create('tbl_jenis_anggota', function (Blueprint $table) {
            $table->id('id_jenis_anggota');
            $table->string('kode_jenis_anggota', 20)->unique();
            $table->string('jenis_anggota', 15);
            $table->integer('max_pinjam');
            $table->string('keterangan', 50)->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_jenis_anggota');
    }
};

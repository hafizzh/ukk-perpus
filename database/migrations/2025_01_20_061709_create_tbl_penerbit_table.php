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
        Schema::create('tbl_penerbit', function (Blueprint $table) {
            $table->id('id_penerbit');
            $table->string('kode_penerbit', 10)->unique();
            $table->string('nama_penerbit', 50)->unique();
            $table->string('alamat_penerbit', 50);
            $table->string('no_telp', 15);
            $table->string('email', 30)->nullable();
            $table->string('fax', 15)->nullable();
            $table->string('website', 50)->nullable();
            $table->string('kontak', 50)->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_penerbit');
    }
};

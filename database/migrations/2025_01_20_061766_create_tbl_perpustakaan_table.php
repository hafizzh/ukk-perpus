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
        Schema::create('tbl_perpustakaan', function (Blueprint $table) {
            $table->id('id_perpustakaan');
            $table->string('nama_perpustakaan', 50)->unique();
            $table->string('nama_pustakawan', 50);
            $table->string('alamat', 50);
            $table->string('email', 50)->unique();
            $table->string('website', 50)->nullable();
            $table->string('no_telp', 15);
            $table->string('keterangan', 50)->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_perpustakaan');
    }
};

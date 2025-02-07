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
        Schema::create('tbl_pengarang', function (Blueprint $table) {
            $table->id('id_pengarang');
            $table->string('kode_pengarang', 10)->unique();
            $table->string('gelar_depan', 10)->nullable();
            $table->string('nama_pengarang', 50)->unique();
            $table->string('gelar_belakang', 10)->nullable();
            $table->string('no_telp', 15);
            $table->string('email', 30)->nullable();
            $table->string('website', 50)->nullable();
            $table->text('biografi')->nullable();
            $table->string('keterangan', 50)->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pengarang');
    }
};

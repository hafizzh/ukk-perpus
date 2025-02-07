<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_pustaka', function (Blueprint $table) {
            $table->id('id_pustaka');
            $table->foreignId('id_ddc');
            $table->foreignId('id_format');
            $table->foreignId('id_penerbit');
            $table->foreignId('id_pengarang');
            $table->string('isbn', 20)->nullable();
            $table->string('judul_pustaka', 100);
            $table->string('tahun_terbit', 5);
            $table->string('keyword', 50)->nullable();
            $table->text('abstraksi')->nullable();
            $table->text('gambar')->nullable();
            $table->integer('harga_buku');
            $table->string('kondisi_buku', 15);
            $table->enum('fp', ['0', '1']);
            $table->tinyInteger('jml_pinjam');
            $table->integer('denda_terlambat');
            $table->integer('denda_hilang');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pustaka');
    }
};

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
        Schema::create('karyawan_tunjangan_detail', function (Blueprint $table) {
            $table->char('kode_tunjangan', 7);
            $table->char('kode_jenis_tunjangan', 4);
            $table->integer('jumlah');
            $table->foreign('kode_tunjangan')->references('kode_tunjangan')->on('karyawan_tunjangan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_jenis_tunjangan')->references('kode_jenis_tunjangan')->on('jenis_tunjangan')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_tunjangan_detail');
    }
};

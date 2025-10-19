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
        Schema::create('presensi_izincuti', function (Blueprint $table) {
            $table->char('kode_izin_cuti', 12)->primary();
            $table->char('nik', 9);
            $table->date('tanggal');
            $table->date('dari');
            $table->date('sampai');
            $table->char('kode_cuti', 3);
            $table->string('keterangan');
            $table->string('keterangan_hrd');
            $table->char('status', 1);
            $table->bigInteger('id_user');
            $table->timestamps();
            $table->foreign('nik')->references('nik')->on('karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_cuti')->references('kode_cuti')->on('cuti')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_izincuti');
    }
};

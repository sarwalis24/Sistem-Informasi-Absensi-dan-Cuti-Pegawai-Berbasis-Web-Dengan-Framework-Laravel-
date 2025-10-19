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
        Schema::create('presensi_jamkerja_bydate', function (Blueprint $table) {
            $table->char('nik', 9);
            $table->date('tanggal');
            $table->char('kode_jam_kerja', 4);
            $table->foreign('nik')->references('nik')->on('karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_jam_kerja')->references('kode_jam_kerja')->on('presensi_jamkerja')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_jamkerja_bydate');
    }
};

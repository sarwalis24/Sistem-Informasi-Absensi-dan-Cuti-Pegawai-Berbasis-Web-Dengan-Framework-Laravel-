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
        Schema::create('presensi_jamkerja', function (Blueprint $table) {
            $table->char('kode_jam_kerja', 4)->primary();
            $table->string('nama_jam_kerja');
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->char('istirahat', 1);
            $table->time('jam_awal_istirahat')->nullable();
            $table->time('jam_akhir_istirahat')->nullable();
            $table->smallInteger('total_jam');
            $table->char('lintashari', 1);
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_jamkerja');
    }
};

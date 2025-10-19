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
        Schema::create('presensi_izinsakit_approve', function (Blueprint $table) {
            $table->id('id_presensi');
            $table->char('kode_izin_sakit', 10);
            $table->foreign('id_presensi')->references('id')->on('presensi')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('kode_izin_sakit')->references('kode_izin_sakit')->on('presensi_izinsakit')->restrictOnDelete()->restrictOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_izinsakit_approve');
    }
};

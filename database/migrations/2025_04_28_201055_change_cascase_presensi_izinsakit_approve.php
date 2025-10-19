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
        Schema::table('presensi_izinsakit_approve', function (Blueprint $table) {
            $table->dropForeign(['id_presensi']);
            $table->dropForeign(['kode_izin_sakit']);
            $table->foreign('id_presensi')->references('id')->on('presensi')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_izin_sakit')->references('kode_izin_sakit')->on('presensi_izinsakit')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_izinsakit_approve', function (Blueprint $table) {
            $table->dropForeign(['id_presensi']);
            $table->foreign('id_presensi')->references('id')->on('presensi')->onDelete('restrict')->onUpdate('cascade');
            $table->dropForeign(['kode_izin_sakit']);
            $table->foreign('kode_izin_sakit')->references('kode_izin_sakit')->on('presensi_izinsakit')->onDelete('restrict')->onUpdate('cascade');
        });
    }
};

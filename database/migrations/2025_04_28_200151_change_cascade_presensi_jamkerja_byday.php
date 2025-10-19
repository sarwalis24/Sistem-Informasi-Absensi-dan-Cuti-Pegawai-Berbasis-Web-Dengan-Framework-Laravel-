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
        Schema::table('presensi_jamkerja_byday', function (Blueprint $table) {
            $table->dropForeign(['nik']);
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_jamkerja_byday', function (Blueprint $table) {
            $table->dropForeign(['nik']);
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('restrict')->onUpdate('cascade');
        });
    }
};

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
        Schema::create('presensi_jamkerja_bydept_detail', function (Blueprint $table) {
            $table->char('kode_jk_dept', 7);
            $table->string('hari');
            $table->char('kode_jam_kerja', 4);
            $table->foreign('kode_jk_dept')->references('kode_jk_dept')->on('presensi_jamkerja_bydept')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_jam_kerja')->references('kode_jam_kerja')->on('presensi_jamkerja')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailsetjamkerjabydepts');
    }
};

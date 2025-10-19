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
        Schema::create('presensi_izinsakit', function (Blueprint $table) {
            $table->char('kode_izin_sakit', 12)->primary();
            $table->char('nik', 9);
            $table->date('tanggal');
            $table->date('dari');
            $table->date('sampai');
            $table->string('doc_sid')->nullable();
            $table->string('keterangan');
            $table->string('keterangan_hrd')->nullable();
            $table->char('status', 1);
            $table->bigInteger('id_user');
            $table->foreign('nik')->references('nik')->on('karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izinsakits');
    }
};

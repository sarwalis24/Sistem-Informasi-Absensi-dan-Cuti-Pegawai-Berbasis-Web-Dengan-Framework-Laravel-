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
        Schema::create('karyawan_penyesuaian_gaji_detail', function (Blueprint $table) {
            $table->char('kode_penyesuaian_gaji', 9);
            $table->char('nik', 9);
            $table->integer('penambah');
            $table->integer('pengurang');
            $table->string('keterangan');
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_penyesuaian_gaji')->references('kode_penyesuaian_gaji')->on('karyawan_penyesuaian_gaji')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_penyesuaian_gaji_detail');
    }
};

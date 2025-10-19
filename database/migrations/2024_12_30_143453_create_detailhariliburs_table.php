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
        Schema::create('hari_libur_detail', function (Blueprint $table) {
            $table->char('kode_libur', 7);
            $table->char('nik', 9);
            $table->foreign('kode_libur')->references('kode_libur')->on('hari_libur')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->foreign('nik')->references('nik')->on('karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hari_libur_detail');
    }
};

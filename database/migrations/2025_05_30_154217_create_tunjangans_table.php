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
        Schema::create('karyawan_tunjangan', function (Blueprint $table) {
            $table->char('kode_tunjangan', 7)->primary();
            $table->char('nik', 10);
            $table->date('tanggal_berlaku');
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_tunjangan');
    }
};

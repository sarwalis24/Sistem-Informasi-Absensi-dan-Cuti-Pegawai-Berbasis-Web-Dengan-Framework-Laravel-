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
        Schema::create('karyawan_penyesuaian_gaji', function (Blueprint $table) {
            $table->char('kode_penyesuaian_gaji',9)->primary(); //PYG062025
            $table->smallInteger('bulan');
            $table->smallInteger('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_penyesuaian_gaji');
    }
};

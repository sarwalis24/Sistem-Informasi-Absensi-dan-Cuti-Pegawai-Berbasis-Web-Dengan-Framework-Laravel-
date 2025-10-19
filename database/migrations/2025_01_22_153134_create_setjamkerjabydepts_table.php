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
        Schema::create('presensi_jamkerja_bydept', function (Blueprint $table) {
            $table->char('kode_jk_dept', 7)->primary();
            $table->char('kode_cabang', 3);
            $table->char('kode_dept', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_jamkerja_bydept');
    }
};

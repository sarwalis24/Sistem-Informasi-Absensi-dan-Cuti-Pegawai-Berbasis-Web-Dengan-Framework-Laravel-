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
        Schema::create('cabang', function (Blueprint $table) {
            $table->char('kode_cabang', 3)->primary();
            $table->string('nama_cabang', 50);
            $table->string('alamat_cabang', 100);
            $table->string('telepon_cabang', 13);
            $table->string('lokasi_cabang');
            $table->smallInteger('radius_cabang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang');
    }
};

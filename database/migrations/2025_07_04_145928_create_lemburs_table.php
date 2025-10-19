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
        Schema::create('lembur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->char('nik', 9);
            $table->dateTime('lembur_mulai');
            $table->dateTime('lembur_selesai');
            $table->datetime('lembur_in')->nullable();
            $table->datetime('lembur_out')->nullable();
            $table->string('foto_lembur_in')->nullable();
            $table->string('foto_lembur_out')->nullable();
            $table->string('lokasi_lembur_in')->nullable();
            $table->string('lokasi_lembur_out')->nullable();
            $table->char('status', 1);
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembur');
    }
};

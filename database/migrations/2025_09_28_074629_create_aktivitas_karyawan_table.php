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
        Schema::create('aktivitas_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20);
            $table->text('aktivitas');
            $table->string('foto')->nullable();
            $table->string('lokasi')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade');

            // Index for better performance
            $table->index('nik');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_karyawan');
    }
};

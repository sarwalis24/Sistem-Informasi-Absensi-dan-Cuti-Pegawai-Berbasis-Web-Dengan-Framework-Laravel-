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
        Schema::table('presensi', function (Blueprint $table) {
            $table->dateTime('istirahat_in')->nullable();
            $table->string('lokasi_istirahat_in')->nullable();
            $table->string('foto_istirahat_in')->nullable();
            $table->dateTime('istirahat_out')->nullable();
            $table->string('lokasi_istirahat_out')->nullable();
            $table->string('foto_istirahat_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropColumn('istirahat_in');
            $table->dropColumn('istirahat_out');
            $table->dropColumn('lokasi_istirahat_in');
            $table->dropColumn('lokasi_istirahat_out');
            $table->dropColumn('foto_istirahat_in');
            $table->dropColumn('foto_istirahat_out');
        });
    }
};

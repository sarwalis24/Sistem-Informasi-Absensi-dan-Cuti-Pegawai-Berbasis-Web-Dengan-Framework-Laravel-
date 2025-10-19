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
        Schema::table('pengaturan_umum', function (Blueprint $table) {
            $table->smallInteger('periode_laporan_dari');
            $table->smallInteger('periode_laporan_sampai');
            $table->boolean('periode_laporan_next_bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_umum', function (Blueprint $table) {
            $table->dropColumn('periode_laporan_dari');
            $table->dropColumn('periode_laporan_sampai');
            $table->dropColumn('periode_laporan_next_bulan');
        });
    }
};

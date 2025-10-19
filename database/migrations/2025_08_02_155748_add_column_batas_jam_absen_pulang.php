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
            $table->smallInteger('batas_jam_absen_pulang')->default(0)->after('batas_jam_absen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_umum', function (Blueprint $table) {
            $table->dropColumn('batas_jam_absen_pulang');
        });
    }
};

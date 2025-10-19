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
            $table->string('wa_api_key')->after('domain_wa_gateway');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_umum', function (Blueprint $table) {
            $table->dropColumn('wa_api_key');
        });
    }
};

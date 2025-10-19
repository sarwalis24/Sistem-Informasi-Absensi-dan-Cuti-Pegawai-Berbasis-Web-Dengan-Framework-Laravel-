p<?php

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
            Schema::create('presensi_izindinas', function (Blueprint $table) {
                $table->char('kode_izin_dinas')->primary();
                $table->date('tanggal');
                $table->date('dari');
                $table->date('sampai');
                $table->char('nik', 9);
                $table->string('keterangan');
                $table->string('keterangan_hrd')->nullable();
                $table->char('status', 1);
                $table->foreign('nik')->references('nik')->on('karyawan')->restrictOnDelete()->cascadeOnUpdate();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('izindinas');
        }
    };

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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->char('nik', 9)->primary();
            $table->string('no_ktp', 16);
            $table->string('nama_karyawan', 100);
            $table->string('tempat_lahir', 20)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->char('jenis_kelamin', 1);
            $table->char('kode_status_kawin', 2)->nullable();
            $table->string('pendidikan_terakhir', 4)->nullable();
            $table->char('kode_cabang', 3);
            $table->char('kode_dept', 3);
            $table->char('kode_jabatan', 3);
            $table->date('tanggal_masuk');
            $table->char('status_karyawan', 1);
            $table->string('foto')->nullable();
            $table->char('kode_jadwal', 5)->nullable();
            $table->smallInteger('pin')->nullable();
            $table->date('tanggal_nonaktif')->nullable();
            $table->date('tanggal_off_gaji')->nullable();
            $table->char('lock_location', 1);
            $table->char('status_aktif_karyawan', 1);
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};

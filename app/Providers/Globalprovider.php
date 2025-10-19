<?php

namespace App\Providers;

use App\Models\Izinabsen;
use App\Models\Izincuti;
use App\Models\Izindinas;
use App\Models\Izinsakit;
use App\Models\Lembur;
use App\Models\Pengaturanumum;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class Globalprovider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Guard $auth): void
    {
        view()->composer('*', function ($view) use ($auth) {
            $notifikasi_izinabsen = Izinabsen::where('status', 0)->count();
            $notifikasi_izinsakit = Izinsakit::where('status', 0)->count();
            $notifikasi_izincuti = Izincuti::where('status', 0)->count();
            $notifikasi_lembur = Lembur::where('status', 0)->count();
            $notifikasi_izin_dinas = Izindinas::where('status', 0)->count();
            $data_izinabsen = Izinabsen::select('presensi_izinabsen.nik', 'nama_karyawan', DB::raw('"i" as status'), 'presensi_izinabsen.created_at')
                ->where('status', 0)
                ->join('karyawan', 'presensi_izinabsen.nik', '=', 'karyawan.nik');
            $data_izinsakit = Izinsakit::select('presensi_izinsakit.nik', 'nama_karyawan', DB::raw('"s" as status'), 'presensi_izinsakit.created_at')
                ->where('status', 0)
                ->join('karyawan', 'presensi_izinsakit.nik', '=', 'karyawan.nik');
            $data_izincuti = Izincuti::select('presensi_izincuti.nik', 'nama_karyawan', DB::raw('"c" as status'), 'presensi_izincuti.created_at')
                ->where('status', 0)
                ->join('karyawan', 'presensi_izincuti.nik', '=', 'karyawan.nik');
            $data_izin_dinas = Izindinas::select('presensi_izindinas.nik', 'nama_karyawan', DB::raw('"d" as status'), 'presensi_izindinas.created_at')
                ->where('status', 0)
                ->join('karyawan', 'presensi_izindinas.nik', '=', 'karyawan.nik');
            $data_izin = $data_izinabsen->unionAll($data_izinsakit)->unionAll($data_izincuti)->unionAll($data_izin_dinas)->get();



            $notifikasi_ajuan_absen = $notifikasi_izinabsen + $notifikasi_izincuti + $notifikasi_izinsakit + $notifikasi_izin_dinas;
            $general_setting = Pengaturanumum::where('id', 1)->first();
            $shareddata = [
                'notifikasi_izinabsen' => $notifikasi_izinabsen,
                'notifikasi_izinsakit' => $notifikasi_izinsakit,
                'notifikasi_izincuti' => $notifikasi_izincuti,
                'notifikasi_lembur' => $notifikasi_lembur,
                'notifikasi_izin_dinas' => $notifikasi_izin_dinas,
                'notifikasi_ajuan_absen' => $notifikasi_ajuan_absen,
                'data_izin' => $data_izin,
                'general_setting' => $general_setting
            ];
            View::share($shareddata);
        });
    }
}

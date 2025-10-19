<?php

namespace App\Http\Controllers;

use App\Charts\JeniskelaminkaryawanChart;
use App\Charts\PendidikankaryawanChart;
use App\Charts\StatusKaryawanChart;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{
    public function index(StatusKaryawanChart $chart, JeniskelaminkaryawanChart $jkchart, PendidikankaryawanChart $pddchart, Request $request)
    {
        $agent = new Agent();
        $user = User::where('id', auth()->user()->id)->first();
        $hari_ini = date("Y-m-d");
        if ($user->hasRole('karyawan')) {
            $userkaryawan = Userkaryawan::where('id_user', auth()->user()->id)->first();
            $data['karyawan'] = Karyawan::where('nik', $userkaryawan->nik)
                ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->first();

            $data['presensi'] = Presensi::where('presensi.nik', $userkaryawan->nik)->where('presensi.tanggal', $hari_ini)->first();
            $data['datapresensi'] = Presensi::join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->where('presensi.nik', $userkaryawan->nik)
                ->leftJoin('presensi_izinabsen_approve', 'presensi.id', '=', 'presensi_izinabsen_approve.id_presensi')
                ->leftJoin('presensi_izinabsen', 'presensi_izinabsen_approve.kode_izin', '=', 'presensi_izinabsen.kode_izin')

                ->leftJoin('presensi_izinsakit_approve', 'presensi.id', '=', 'presensi_izinsakit_approve.id_presensi')
                ->leftJoin('presensi_izinsakit', 'presensi_izinsakit_approve.kode_izin_sakit', '=', 'presensi_izinsakit.kode_izin_sakit')

                ->leftJoin('presensi_izincuti_approve', 'presensi.id', '=', 'presensi_izincuti_approve.id_presensi')
                ->leftJoin('presensi_izincuti', 'presensi_izincuti_approve.kode_izin_cuti', '=', 'presensi_izincuti.kode_izin_cuti')
                ->select(
                    'presensi.*',
                    'presensi_jamkerja.nama_jam_kerja',
                    'presensi_jamkerja.jam_masuk',
                    'presensi_jamkerja.jam_pulang',
                    'presensi_jamkerja.total_jam',
                    'presensi_jamkerja.lintashari',
                    'presensi_izinabsen.keterangan as keterangan_izin',
                    'presensi_izinsakit.keterangan as keterangan_izin_sakit',
                    'presensi_izincuti.keterangan as keterangan_izin_cuti',
                )
                ->orderBy('tanggal', 'desc')
                ->limit(30)
                ->get();
            $data['rekappresensi'] = Presensi::select(
                DB::raw("SUM(IF(status='h',1,0)) as hadir"),
                DB::raw("SUM(IF(status='i',1,0)) as izin"),
                DB::raw("SUM(IF(status='s',1,0)) as sakit"),
                DB::raw("SUM(IF(status='a',1,0)) as alpa"),
                DB::raw("SUM(IF(status='c',1,0)) as cuti")
            )
                ->groupBy('presensi.nik')
                ->whereRaw('MONTH(presensi.tanggal) = MONTH(?)', [$hari_ini])
                ->whereRaw('YEAR(presensi.tanggal) = YEAR(?)', [$hari_ini])
                ->where('presensi.nik', $userkaryawan->nik)
                ->first();

            $data['lembur'] = Lembur::where('nik', $userkaryawan->nik)->where('status', 1)
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();
            $data['notiflembur'] = Lembur::where('nik', $userkaryawan->nik)
                ->where('status', 1)
                ->where('lembur_in', null)
                ->orWhere('lembur_out', null)
                ->where('status', 1)
                ->count();
            return view('dashboard.karyawan', $data);
        } else {

            //Dashboard Admin
            $sk = new Karyawan();
            $data['status_karyawan'] = $sk->getRekapstatuskaryawan($request);
            $data['chart'] = $chart->build($request);
            $data['jkchart'] = $jkchart->build($request);
            $data['pddchart'] = $pddchart->build($request);

            $queryPresensi = Presensi::query();
            $queryPresensi->join('karyawan', 'presensi.nik', '=', 'karyawan.nik');
            $queryPresensi->select(
                DB::raw("SUM(IF(status='h',1,0)) as hadir"),
                DB::raw("SUM(IF(status='i',1,0)) as izin"),
                DB::raw("SUM(IF(status='s',1,0)) as sakit"),
                DB::raw("SUM(IF(status='a',1,0)) as alpa"),
                DB::raw("SUM(IF(status='c',1,0)) as cuti")
            );
            if (!empty($request->tanggal)) {
                $queryPresensi->where('tanggal', $request->tanggal);
            } else {
                $queryPresensi->where('tanggal', date('Y-m-d'));
            }

            if (!empty($request->kode_cabang)) {
                $queryPresensi->where('karyawan.kode_cabang', $request->kode_cabang);
            }

            if (!empty($request->kode_dept)) {
                $queryPresensi->where('karyawan.kode_dept', $request->kode_dept);
            }
            $data['rekappresensi'] = $queryPresensi->first();
            $data['departemen'] = Departemen::orderBy('kode_dept')->get();
            $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
            // dd($data['rekappresensi']);
            return view('dashboard.dashboard', $data);
        }
    }
}

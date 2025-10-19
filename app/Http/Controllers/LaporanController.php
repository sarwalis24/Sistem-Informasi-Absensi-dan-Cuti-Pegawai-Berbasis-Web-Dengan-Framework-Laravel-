<?php

namespace App\Http\Controllers;

use App\Models\Bpjskesehatan;
use App\Models\Bpjstenagakerja;
use App\Models\Cabang;
use App\Models\Denda;
use App\Models\Departemen;
use App\Models\Detailpenyesuaiangaji;
use App\Models\Detailtunjangan;
use App\Models\Gajipokok;
use App\Models\Jenistunjangan;
use App\Models\Karyawan;
use App\Models\Pengaturanumum;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function presensi()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cabang = Cabang::orderBy('kode_cabang')->get();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = $cabang;
        $data['departemen'] = $departemen;
        return view('laporan.presensi', $data);
    }


    public function cetakpresensi(Request $request)
    {

        $user = User::where('id', Auth::user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $generalsetting = Pengaturanumum::where('id', 1)->first();
        $periode_laporan_dari = $generalsetting->periode_laporan_dari;
        $periode_laporan_sampai = $generalsetting->periode_laporan_sampai;
        $periode_laporan_lintas_bulan = $generalsetting->periode_laporan_next_bulan;
        if ($request->periode_laporan == 1) {
            if ($periode_laporan_lintas_bulan == 1) {
                if ($request->bulan == 1) {
                    $bulan = 12;
                    $tahun = $request->tahun - 1;
                } else {
                    $bulan = $request->bulan - 1;
                    $tahun = $request->tahun;
                }
            } else {
                $bulan = $request->bulan;
                $tahun = $request->tahun;
            }

            // Menambahkan nol di depan bulan jika bulan kurang dari 10

            $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $periode_dari = $tahun . '-' . $bulan . '-' . $periode_laporan_dari;
            $periode_sampai = $request->tahun . '-' . $request->bulan . '-' . $periode_laporan_sampai;
        } else {
            // Menambahkan nol di depan bulan jika bulan kurang dari 10

            $bulan = str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
            $periode_dari = $request->tahun . '-' . $bulan . '-01';
            $periode_sampai = date('Y-m-t', strtotime($periode_dari));
        }




        $presensi_detail  = Presensi::join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->leftJoin('presensi_izinabsen_approve', 'presensi.id', '=', 'presensi_izinabsen_approve.id_presensi')
            ->leftJoin('presensi_izinabsen', 'presensi_izinabsen_approve.kode_izin', '=', 'presensi_izinabsen.kode_izin')
            ->leftJoin('presensi_izinsakit_approve', 'presensi.id', '=', 'presensi_izinsakit_approve.id_presensi')
            ->leftJoin('presensi_izinsakit', 'presensi_izinsakit_approve.kode_izin_sakit', '=', 'presensi_izinsakit.kode_izin_sakit')
            ->leftJoin('presensi_izincuti_approve', 'presensi.id', '=', 'presensi_izincuti_approve.id_presensi')
            ->leftJoin('presensi_izincuti', 'presensi_izincuti_approve.kode_izin_cuti', '=', 'presensi_izincuti.kode_izin_cuti')
            ->select(
                'presensi.*',
                'nama_jam_kerja',
                'jam_masuk',
                'jam_pulang',
                'istirahat',
                'jam_awal_istirahat',
                'jam_akhir_istirahat',
                'lintashari',
                'total_jam',
                'presensi_izinabsen.keterangan as keterangan_izin_absen',
                'presensi_izinsakit.keterangan as keterangan_izin_sakit',
                'presensi_izincuti.keterangan as keterangan_izin_cuti'
            )
            ->whereBetween('presensi.tanggal', [$periode_dari, $periode_sampai]);


        $gaji_pokok = Gajipokok::select(
            'nik',
            'jumlah'
        )
            ->whereIn('kode_gaji', function ($query) use ($periode_sampai) {
                $query->select(DB::raw('MAX(kode_gaji)'))
                    ->from('karyawan_gaji_pokok')
                    ->where('tanggal_berlaku', '<=', $periode_sampai)
                    ->groupBy('nik');
            });



        $bpjs_kesehatan = Bpjskesehatan::select(
            'nik',
            'jumlah'
        )
            ->whereIn('kode_bpjs_kesehatan', function ($query) use ($periode_sampai) {
                $query->select(DB::raw('MAX(kode_bpjs_kesehatan)'))
                    ->from('karyawan_bpjskesehatan')
                    ->where('tanggal_berlaku', '<=', $periode_sampai)
                    ->groupBy('nik');
            });


        $bpjs_tenagakerja = Bpjstenagakerja::select(
            'nik',
            'jumlah'
        )
            ->whereIn('kode_bpjs_tk', function ($query) use ($periode_sampai) {
                $query->select(DB::raw('MAX(kode_bpjs_tk)'))
                    ->from('karyawan_bpjstenagakerja')
                    ->where('tanggal_berlaku', '<=', $periode_sampai)
                    ->groupBy('nik');
            });


        //Tunjangan
        $jenis_tunjangan = Jenistunjangan::orderBy('kode_jenis_tunjangan')->get();
        $select_tunjangan = [];
        $select_field_tunjangan = [];
        foreach ($jenis_tunjangan as $d) {
            $select_tunjangan[] = DB::raw('SUM(IF(karyawan_tunjangan_detail.kode_jenis_tunjangan = "' . $d->kode_jenis_tunjangan . '", karyawan_tunjangan_detail.jumlah, 0)) as jumlah_' . $d->kode_jenis_tunjangan);
            $select_field_tunjangan[] = 'jumlah_' . $d->kode_jenis_tunjangan;
        }
        $tunjangan = Detailtunjangan::query();
        $tunjangan->join('karyawan_tunjangan', 'karyawan_tunjangan_detail.kode_tunjangan', '=', 'karyawan_tunjangan.kode_tunjangan');
        $tunjangan->select(
            'karyawan_tunjangan.nik',
            ...$select_tunjangan
        );
        $tunjangan->whereIn('karyawan_tunjangan_detail.kode_tunjangan', function ($query) use ($periode_sampai) {
            $query->select(DB::raw('MAX(kode_tunjangan)'))
                ->from('karyawan_tunjangan')
                ->where('tanggal_berlaku', '<=', $periode_sampai)
                ->groupBy('nik');
        });

        $tunjangan->groupBy('karyawan_tunjangan.nik');


        $penyesuaian_gaji = Detailpenyesuaiangaji::select('nik', 'penambah', 'pengurang')
            ->join('karyawan_penyesuaian_gaji', 'karyawan_penyesuaian_gaji_detail.kode_penyesuaian_gaji', '=', 'karyawan_penyesuaian_gaji.kode_penyesuaian_gaji')
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun);

        $q_presensi = Karyawan::query();
        $q_presensi->select(
            'karyawan.nik',
            'nama_karyawan',
            'nama_jabatan',
            'karyawan.kode_dept',
            'nama_dept',
            'karyawan.kode_cabang',
            'presensi.tanggal',
            'presensi.status',
            'presensi.kode_jam_kerja',
            'presensi.nama_jam_kerja',
            'presensi.jam_masuk',
            'presensi.jam_pulang',
            'presensi.jam_in',
            'presensi.jam_out',
            'presensi.istirahat',
            'presensi.jam_awal_istirahat',
            'presensi.jam_akhir_istirahat',
            'presensi.lintashari',
            'presensi.keterangan_izin_absen',
            'presensi.keterangan_izin_sakit',
            'presensi.keterangan_izin_cuti',
            'presensi.total_jam',
            'gaji_pokok.jumlah as gaji_pokok',
            'bpjs_kesehatan.jumlah as bpjs_kesehatan',
            'bpjs_tenagakerja.jumlah as bpjs_tenagakerja',
            'penambah',
            'pengurang',
            ...$select_field_tunjangan
        );
        $q_presensi->leftJoin('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan');
        $q_presensi->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $q_presensi->leftJoinSub($presensi_detail, 'presensi', function ($join) {
            $join->on('karyawan.nik', '=', 'presensi.nik');
        });
        $q_presensi->leftJoinSub($gaji_pokok, 'gaji_pokok', function ($join) {
            $join->on('karyawan.nik', '=', 'gaji_pokok.nik');
        });


        $q_presensi->leftJoinSub($bpjs_kesehatan, 'bpjs_kesehatan', function ($join) {
            $join->on('karyawan.nik', '=', 'bpjs_kesehatan.nik');
        });

        $q_presensi->leftJoinSub($bpjs_tenagakerja, 'bpjs_tenagakerja', function ($join) {
            $join->on('karyawan.nik', '=', 'bpjs_tenagakerja.nik');
        });


        $q_presensi->leftJoinSub($tunjangan, 'tunjangan', function ($join) {
            $join->on('karyawan.nik', '=', 'tunjangan.nik');
        });

        $q_presensi->leftJoinSub($penyesuaian_gaji, 'penyesuaian_gaji', function ($join) {
            $join->on('karyawan.nik', '=', 'penyesuaian_gaji.nik');
        });

        if (!empty($request->kode_cabang)) {
            $q_presensi->where('karyawan.kode_cabang', $request->kode_cabang);
        }
        if (!empty($request->kode_dept)) {
            $q_presensi->where('karyawan.kode_dept', $request->kode_dept);
        }

        if (!empty($request->nik)) {
            $q_presensi->where('karyawan.nik', $request->nik);
        }

        if ($user->hasRole('karyawan')) {
            $q_presensi->where('karyawan.nik', $userkaryawan->nik);
        }
        $q_presensi->orderBy('karyawan.nama_karyawan');
        $q_presensi->orderBy('presensi.tanggal', 'asc');
        $presensi = $q_presensi->get();


        $data['periode_dari'] = $periode_dari;
        $data['periode_sampai'] = $periode_sampai;
        $data['jmlhari'] = hitungJumlahHari($periode_dari, $periode_sampai) + 1;
        $data['denda_list'] = Denda::all()->toArray();
        $data['datalibur'] = getdatalibur($periode_dari, $periode_sampai);
        $data['datalembur'] = getlembur($periode_dari, $periode_sampai);
        $data['generalsetting'] = $generalsetting;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Presensi $periode_dari - $periode_sampai.xls");
        }
        if (!empty($request->nik) && $request->format_laporan == 1) {
            $karyawan = Karyawan::join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->where('karyawan.nik', $request->nik)
                ->first();
            $data['karyawan'] = $karyawan;
            $data['presensi'] = $presensi;
            return view('laporan.presensi_karyawan_cetak', $data);
        } else {
            $laporan_presensi = $presensi->groupBy('nik')->map(function ($rows) use ($jenis_tunjangan) {
                $data = [
                    'nik' => $rows->first()->nik,
                    'nama_karyawan' => $rows->first()->nama_karyawan,
                    'nama_jabatan' => $rows->first()->nama_jabatan,
                    'kode_dept' => $rows->first()->kode_dept,
                    'nama_dept' => $rows->first()->nama_dept,
                    'kode_cabang' => $rows->first()->kode_cabang,
                    'gaji_pokok' => $rows->first()->gaji_pokok,
                    'bpjs_kesehatan' => $rows->first()->bpjs_kesehatan,
                    'bpjs_tenagakerja' => $rows->first()->bpjs_tenagakerja,
                    'penambah' => $rows->first()->penambah,
                    'pengurang' => $rows->first()->pengurang,

                ];

                foreach ($jenis_tunjangan as $j) {
                    $data = [
                        ...$data,
                        $j->kode_jenis_tunjangan => $rows->first()->{"jumlah_" . $j->kode_jenis_tunjangan}
                    ];
                }

                foreach ($rows as $row) {
                    $data[$row->tanggal] = [
                        'status' => $row->status,
                        'kode_jam_kerja' => $row->kode_jam_kerja,
                        'nama_jam_kerja' => $row->nama_jam_kerja,
                        'jam_masuk' => $row->jam_masuk,
                        'jam_pulang' => $row->jam_pulang,
                        'jam_in' => $row->jam_in,
                        'jam_out' => $row->jam_out,
                        'istirahat' => $row->istirahat,
                        'jam_awal_istirahat' => $row->jam_awal_istirahat,
                        'jam_akhir_istirahat' => $row->jam_akhir_istirahat,
                        'lintashari' => $row->lintashari,
                        'keterangan_izin_absen' => $row->keterangan_izin_absen,
                        'keterangan_izin_sakit' => $row->keterangan_izin_sakit,
                        'keterangan_izin_cuti' => $row->keterangan_izin_cuti,
                        'total_jam' => $row->total_jam
                    ];
                }
                return $data;
            });
            $data['laporan_presensi'] = $laporan_presensi;
            $data['jenis_tunjangan'] = $jenis_tunjangan;


            if ($user->hasRole('karyawan')) {
                //dd($data);
                return view('laporan.slip_karyawan_cetak', $data);
            } else {
                if ($request->format_laporan == 1) {
                    return view('laporan.presensi_cetak', $data);
                } else if ($request->format_laporan == 2) {
                    return view('laporan.gaji_cetak', $data);
                } else if ($request->format_laporan == 3) {
                    return view('laporan.slip_cetak', $data);
                }
            }
        }
    }
}

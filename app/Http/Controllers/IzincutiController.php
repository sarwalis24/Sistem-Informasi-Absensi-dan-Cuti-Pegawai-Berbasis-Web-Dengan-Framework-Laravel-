<?php

namespace App\Http\Controllers;

use App\Models\Approveizincuti;
use App\Models\Cabang;
use App\Models\Cuti;
use App\Models\Departemen;
use App\Models\Detailsetjamkerjabydept;
use App\Models\Izinabsen;
use App\Models\Izincuti;
use App\Models\Izinsakit;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Setjamkerjabydate;
use App\Models\Setjamkerjabyday;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzincutiController extends Controller
{
    public function index(Request $request)
    {
        $qcuti = Izincuti::query();
        $qcuti->join('karyawan', 'presensi_izincuti.nik', '=', 'karyawan.nik');
        $qcuti->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan');
        $qcuti->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $qcuti->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $qcuti->join('cuti', 'presensi_izincuti.kode_cuti', '=', 'cuti.kode_cuti');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $qcuti->whereBetween('izincuti.dari', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nama_karyawan)) {
            $qcuti->where('karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        if (!empty($request->kode_cabang)) {
            $qcuti->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $qcuti->where('karyawan.kode_dept', $request->kode_dept);
        }

        if (!empty($request->status) || $request->status === '0') {
            $qcuti->where('presensi_izincuti.status', $request->status);
        }

        $qcuti->orderBy('presensi_izincuti.status');
        $qcuti->orderBy('presensi_izincuti.dari', 'desc');
        $cuti = $qcuti->paginate(15);
        $cuti->appends($request->all());
        $data['izincuti'] = $cuti;
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        return view('izincuti.index', $data);
    }


    public function create()
    {

        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();
        $data['jenis_cuti'] = Cuti::orderBy('kode_cuti')->get();
        $data['karyawan'] = $karyawan;

        $user = User::where('id', '=', auth()->user()->id)->first();
        if ($user->hasRole('karyawan')) {
            return view('izincuti.create-mobile', $data);
        }
        return view('izincuti.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $nik = $user->hasRole('karyawan') ? $userkaryawan->nik : $request->nik;
        if ($role == 'karyawan') {
            $request->validate([
                'dari' => 'required',
                'sampai' => 'required',
                'keterangan' => 'required',
                'kode_cuti' => 'required',
            ]);
        } else {
            $request->validate([
                'nik' => 'required',
                'dari' => 'required',
                'sampai' => 'required',
                'keterangan' => 'required',
                'kode_cuti' => 'required',
            ]);
        }


        $format = "IC" . date('ym', strtotime($request->dari));
        DB::beginTransaction();
        try {
            $cek_izin_absen = Izinabsen::where('nik', $nik)
                ->whereBetween('dari', [$request->dari, $request->sampai])
                ->orWhereBetween('sampai', [$request->dari, $request->sampai])
                ->where('nik', $nik)
                ->first();

            $cek_izin_sakit = Izinsakit::where('nik', $nik)
                ->whereBetween('dari', [$request->dari, $request->sampai])
                ->orWhereBetween('sampai', [$request->dari, $request->sampai])
                ->where('nik', $nik)
                ->first();

            $cek_izin_cuti = Izincuti::where('nik', $nik)
                ->whereBetween('dari', [$request->dari, $request->sampai])
                ->orWhereBetween('sampai', [$request->dari, $request->sampai])
                ->where('nik', $nik)
                ->first();

            if ($cek_izin_absen) {
                return Redirect::back()->with(messageError('Anda Sudah Mengajukan Izin Absen/Sakit/Cuti Pada Rentang Tanggal Tersebut!'));
            } else if ($cek_izin_sakit) {
                return Redirect::back()->with(messageError('Anda Sudah Mengajukan Izin Absen/Sakit/Cuti Absen Pada Rentang Tanggal Tersebut!'));
            } else if ($cek_izin_cuti) {
                return Redirect::back()->with(messageError('Anda Sudah Mengajukan Izin Absen/Sakit/Cuti Absen Pada Rentang Tanggal Tersebut!'));
            }
            $lastizincuti = Izincuti::select('kode_izin_cuti')
                ->whereRaw('LEFT(kode_izin_cuti,6)="' . $format . '"')
                ->orderBy("kode_izin_cuti", "desc")
                ->first();
            $last_kode_izin_cuti = $lastizincuti != null ? $lastizincuti->kode_izin_cuti : '';
            $kode_izin_cuti  = buatkode($last_kode_izin_cuti, "IC"  . date('ym', strtotime($request->dari)), 4);


            $dataizincuti = [
                'kode_izin_cuti' => $kode_izin_cuti,
                'nik' => $nik,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'kode_cuti' => $request->kode_cuti,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'id_user' => $user->id,
            ];


            Izincuti::create($dataizincuti);
            DB::commit();
            if ($role == 'karyawan') {
                return Redirect::route('pengajuanizin.index')->with(messageSuccess('Data Berhasil Disimpan'));
            } else {
                return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        $izincuti = Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->first();
        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();
        $data['karyawan'] = $karyawan;
        $data['izincuti'] = $izincuti;
        $data['jenis_cuti'] = Cuti::orderBy('kode_cuti')->get();
        return view('izincuti.edit', $data);
    }


    public function update(Request $request, $kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        $request->validate([
            'nik' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
            'kode_cuti' => 'required',
        ]);
        DB::beginTransaction();
        try {
            Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->update([
                'nik' => $request->nik,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'kode_cuti' => $request->kode_cuti,
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function approve($kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        $izincuti = Izincuti::where('kode_izin_cuti', $kode_izin_cuti)
            ->join('karyawan', 'presensi_izincuti.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['izincuti'] = $izincuti;
        return view('izincuti.approve', $data);
    }


    public function storeapprove(Request $request, $kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        $izincuti = Izincuti::where('kode_izin_cuti', $kode_izin_cuti)
            ->join('karyawan', 'presensi_izincuti.nik', '=', 'karyawan.nik')
            ->first();
        $dari = $izincuti->dari;
        $sampai = $izincuti->sampai;
        $nik = $izincuti->nik;
        $kode_dept = $izincuti->kode_dept;
        $error = '';
        DB::beginTransaction();
        try {
            if (isset($request->approve)) {
                // echo 'test';
                Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->update([
                    'status' => 1
                ]);

                while (strtotime($dari) <= strtotime($sampai)) {

                    //Cek Jadwal Pada Setiap tanggal
                    $namahari = getnamaHari(date('D', strtotime($dari)));

                    $jamkerja = Setjamkerjabydate::join('presensi_jamkerja', 'presensi_jamkerja_bydate.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                        ->where('nik', $izincuti->nik)
                        ->where('tanggal', $dari)
                        ->first();
                    if ($jamkerja == null) {

                        $jamkerja = Setjamkerjabyday::join('presensi_jamkerja', 'presensi_jamkerja_byday.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                            ->where('nik', $izincuti->nik)->where('hari', $namahari)
                            ->first();
                    }

                    if ($jamkerja == null) {
                        $jamkerja = Detailsetjamkerjabydept::join('presensi_jamkerja_bydept', 'presensi_jamkerja_bydept_detail.kode_jk_dept', '=', 'presensi_jamkerja_bydept.kode_jk_dept')
                            ->join('presensi_jamkerja', 'presensi_jamkerja_bydept_detail.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                            ->where('kode_dept', $kode_dept)
                            ->where('kode_cabang', $izincuti->kode_cabang)
                            ->where('hari', $namahari)->first();
                    }

                    if ($jamkerja == null) {
                        $error .= 'Jam Kerja pada Tanggal ' . $dari . ' Belum Di Set! <br>';
                    } else {
                        // dd($request->all());
                        // dd(isset($request->approve));
                        $presensi = Presensi::create([
                            'nik' => $nik,
                            'tanggal' => $dari,
                            'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                            'status' => 'c',
                        ]);

                        Approveizincuti::create([
                            'id_presensi' => $presensi->id,
                            'kode_izin_cuti' => $kode_izin_cuti,
                        ]);
                    }


                    $dari = date('Y-m-d', strtotime($dari . ' +1 day'));
                }
            } else {
                Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->update([
                    'status' => 2
                ]);
            }
            if (!empty($error)) {
                DB::rollBack();
                return Redirect::back()->with(messageError($error));
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancelapprove($kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        $presensi = Approveizincuti::where('kode_izin_cuti', $kode_izin_cuti)->get();
        try {
            Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->update([
                'status' => 0
            ]);
            Approveizincuti::where('kode_izin_cuti', $kode_izin_cuti)->delete();
            Presensi::whereIn('id', $presensi->pluck('id_presensi'))->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        try {
            Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        $izincuti = Izincuti::where('kode_izin_cuti', $kode_izin_cuti)
            ->join('karyawan', 'presensi_izincuti.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['izincuti'] = $izincuti;
        return view('izincuti.show', $data);
    }

    public function getsisaharicuti(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $nik = $user->hasRole('karyawan') ? $userkaryawan->nik : $request->nik;
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $tahun_cuti = date('Y', strtotime($tanggal));
        $kode_cuti = $request->kode_cuti;
        $cuti = Cuti::where('kode_cuti', $kode_cuti)->first();
        $jml_hari_max = $cuti->jumlah_hari;
        if ($cuti->kode_cuti == "C01") {
            $cek_cuti_dipakai = Approveizincuti::join('presensi', 'presensi_izincuti_approve.id_presensi', '=', 'presensi.id')
                ->where('presensi.nik', $nik)
                ->whereRaw("YEAR(presensi.tanggal) = $tahun_cuti")
                ->count();
            $sisa_cuti = $jml_hari_max - $cek_cuti_dipakai;
            $message = 'Sisa Cuti ' . $cuti->jenis_cuti . ' Anda Adalah ' . $sisa_cuti . ' Hari Lagi';
        } else {
            $sisa_cuti = $jml_hari_max;
            $message = "Batas Maksimal Cuti " . $cuti->jenis_cuti . " Anda Adalah " . $jml_hari_max . " Hari";
        }
        return response()->json(['status' => true, 'sisa_cuti' => $sisa_cuti, 'message' => $message]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Approveizinabsen;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailsetjamkerjabydept;
use App\Models\Izinabsen;
use App\Models\Izincuti;
use App\Models\Izinsakit;
use App\Models\Karyawan;
use App\Models\Pengaturanumum;
use App\Models\Presensi;
use App\Models\Setjamkerjabydate;
use App\Models\Setjamkerjabyday;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Jenssegers\Agent\Agent;

class IzinabsenController extends Controller
{
    public function index(Request $request)
    {

        $qizin = Izinabsen::query();
        $qizin->join('karyawan', 'presensi_izinabsen.nik', '=', 'karyawan.nik');
        $qizin->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan');
        $qizin->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $qizin->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $qizin->whereBetween('presensi_izinabsen.tanggal', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nama_karyawan)) {
            $qizin->where('karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_cabang)) {
            $qizin->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $qizin->where('karyawan.kode_dept', $request->kode_dept);
        }

        if (!empty($request->status) || $request->status === '0') {
            $qizin->where('presensi_izinabsen.status', $request->status);
        }
        $qizin->orderBy('presensi_izinabsen.status');
        $qizin->orderBy('presensi_izinabsen.tanggal', 'desc');
        $izinabsen = $qizin->paginate(15);
        $izinabsen->appends($request->all());

        $data['izinabsen'] = $izinabsen;
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        return view('izinabsen.index', $data);
    }

    public function create()
    {
        $user = User::where('id', '=', auth()->user()->id)->first();
        $agent = new Agent();
        if ($user->hasRole('karyawan')) {
            return view('izinabsen.create-mobile');
        }
        $user = User::where('id', '=', auth()->user()->id)->first();
        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();

        $data['karyawan'] = $karyawan;


        return view('izinabsen.create', $data);
    }

    public function edit($kode_izin)
    {
        $user = User::where('id', '=', auth()->user()->id)->first();
        $kode_izin = Crypt::decrypt($kode_izin);
        $izinabsen = Izinabsen::where('kode_izin', $kode_izin)->first();
        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();
        $data['karyawan'] = $karyawan;
        $data['izinabsen'] = $izinabsen;

        return view('izinabsen.edit', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $role = $user->getRoleNames()->first();
        $general_setting = Pengaturanumum::where('id', 1)->first();
        $nik = $user->hasRole('karyawan') ? $userkaryawan->nik : $request->nik;

        if ($role == 'karyawan') {
            $request->validate([
                'dari' => 'required',
                'sampai' => 'required',
                'keterangan' => 'required',
            ]);
        } else {
            $request->validate([
                'nik' => 'required',
                'dari' => 'required',
                'sampai' => 'required',
                'keterangan' => 'required',
            ]);
        }

        DB::beginTransaction();
        try {
            $jmlhari = hitungHari($request->dari, $request->sampai);
            $batasi_hari_izin = $general_setting->batasi_hari_izin;
            $jml_hari_izin_max = $general_setting->jml_hari_izin_max;

            if ($jmlhari > $jml_hari_izin_max && $batasi_hari_izin == 1) {
                return Redirect::back()->with(messageError('Tidak Boleh Lebih dari ' . $jml_hari_izin_max . ' Hari!'));
            }

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

            //dd($nik . "-" . $cek_izin_absen . "-" . $cek_izin_sakit . "-" . $cek_izin_cuti);
            if ($cek_izin_absen) {
                return Redirect::back()->with(messageError('Anda Sudah Mengajukan Izin Absen/Sakit/Cuti Pada Rentang Tanggal Tersebut!'));
            } else if ($cek_izin_sakit) {
                return Redirect::back()->with(messageError('Anda Sudah Mengajukan Izin Absen/Sakit/Cuti Absen Pada Rentang Tanggal Tersebut!'));
            } else if ($cek_izin_cuti) {
                return Redirect::back()->with(messageError('Anda Sudah Mengajukan Izin Absen/Sakit/Cuti Absen Pada Rentang Tanggal Tersebut!'));
            }
            $lastizin = Izinabsen::select('kode_izin')
                ->whereRaw('YEAR(dari)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('MONTH(dari)="' . date('m', strtotime($request->dari)) . '"')
                ->orderBy("kode_izin", "desc")
                ->first();
            $last_kode_izin = $lastizin != null ? $lastizin->kode_izin : '';
            $kode_izin  = buatkode($last_kode_izin, "IA"  . date('ym', strtotime($request->dari)), 4);

            Izinabsen::create([
                'kode_izin' => $kode_izin,
                'nik' => $nik,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'status' => 0,
            ]);
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

    public function approve($kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        $izinabsen = Izinabsen::where('kode_izin', $kode_izin)
            ->join('karyawan', 'presensi_izinabsen.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['izinabsen'] = $izinabsen;
        return view('izinabsen.approve', $data);
    }

    public function storeapprove(Request $request, $kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        $izinabsen = Izinabsen::where('kode_izin', $kode_izin)
            ->join('karyawan', 'presensi_izinabsen.nik', '=', 'karyawan.nik')
            ->first();
        $dari = $izinabsen->dari;
        $sampai = $izinabsen->sampai;
        $nik = $izinabsen->nik;
        $kode_dept = $izinabsen->kode_dept;
        $error = '';
        DB::beginTransaction();
        try {
            if (isset($request->approve)) {
                // echo 'test';


                Izinabsen::where('kode_izin', $kode_izin)->update([
                    'status' => 1
                ]);

                while (strtotime($dari) <= strtotime($sampai)) {

                    //Cek Jadwal Pada Setiap tanggal
                    $namahari = getnamaHari(date('D', strtotime($dari)));

                    $jamkerja = Setjamkerjabydate::join('presensi_jamkerja', 'presensi_jamkerja_bydate.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                        ->where('nik', $izinabsen->nik)
                        ->where('tanggal', $dari)
                        ->first();
                    if ($jamkerja == null) {
                        $jamkerja = Setjamkerjabyday::join('presensi_jamkerja', 'presensi_jamkerja_byday.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                            ->where('nik', $izinabsen->nik)->where('hari', $namahari)
                            ->first();
                    }

                    if ($jamkerja == null) {
                        $jamkerja = Detailsetjamkerjabydept::join('presensi_jamkerja_bydept', 'presensi_jamkerja_bydept_detail.kode_jk_dept', '=', 'presensi_jamkerja_bydept.kode_jk_dept')
                            ->join('presensi_jamkerja', 'presensi_jamkerja_bydept_detail.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                            ->where('kode_dept', $kode_dept)
                            ->where('kode_cabang', $izinabsen->kode_cabang)
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
                            'status' => 'i',
                        ]);

                        Approveizinabsen::create([
                            'id_presensi' => $presensi->id,
                            'kode_izin' => $kode_izin,
                        ]);
                    }


                    $dari = date('Y-m-d', strtotime($dari . ' +1 day'));
                }
            } else {
                Izinabsen::where('kode_izin', $kode_izin)->update([
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

    public function cancelapprove($kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        $presensi = Approveizinabsen::where('kode_izin', $kode_izin)->get();
        try {
            Izinabsen::where('kode_izin', $kode_izin)->update([
                'status' => 0
            ]);
            Approveizinabsen::where('kode_izin', $kode_izin)->delete();
            Presensi::whereIn('id', $presensi->pluck('id_presensi'))->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        try {
            Izinabsen::where('kode_izin', $kode_izin)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function update(Request $request, $kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        $request->validate([
            'nik' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {
            Izinabsen::where('kode_izin', $kode_izin)->update([
                'nik' => $request->nik,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        $izinabsen = Izinabsen::where('kode_izin', $kode_izin)
            ->join('karyawan', 'presensi_izinabsen.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['izinabsen'] = $izinabsen;
        return view('izinabsen.show', $data);
    }
}

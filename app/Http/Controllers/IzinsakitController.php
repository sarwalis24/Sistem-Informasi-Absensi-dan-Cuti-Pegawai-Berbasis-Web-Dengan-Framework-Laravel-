<?php

namespace App\Http\Controllers;

use App\Models\Approveizinsakit;
use App\Models\Cabang;
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
use Illuminate\Support\Facades\Storage;

class IzinsakitController extends Controller
{
    public function index(Request $request)
    {
        $qizin = Izinsakit::query();
        $qizin->join('karyawan', 'presensi_izinsakit.nik', '=', 'karyawan.nik');
        $qizin->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan');
        $qizin->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $qizin->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $qizin->whereBetween('presensi_izinsakit.tanggal', [$request->dari, $request->sampai]);
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
            $qizin->where('presensi_izinsakit.status', $request->status);
        }

        $qizin->orderBy('presensi_izinsakit.status');
        $qizin->orderBy('presensi_izinsakit.tanggal', 'desc');
        $izinsakit = $qizin->paginate(15);
        $izinsakit->appends($request->all());

        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['izinsakit'] = $izinsakit;
        return view('izinsakit.index', $data);
    }

    public function create()
    {
        $user = User::where('id', '=', auth()->user()->id)->first();
        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();

        $data['karyawan'] = $karyawan;

        if ($user->hasRole('karyawan')) {
            return view('izinsakit.create-mobile', $data);
        }

        return view('izinsakit.create', $data);
    }

    public function edit($kode_izin_sakit)
    {
        $user = User::where('id', '=', auth()->user()->id)->first();
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        $izinsakit = Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->first();
        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();
        $data['karyawan'] = $karyawan;
        $data['izinsakit'] = $izinsakit;

        return view('izinsakit.edit', $data);
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
            $lastizinsakit = Izinsakit::select('kode_izin_sakit')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->dari)) . '"')
                ->orderBy("kode_izin_sakit", "desc")
                ->first();
            $last_kode_izin_sakit = $lastizinsakit != null ? $lastizinsakit->kode_izin_sakit : '';
            $kode_izin_sakit  = buatkode($last_kode_izin_sakit, "IS"  . date('ym', strtotime($request->dari)), 4);


            $data_sid = [];
            if ($request->hasfile('sid')) {
                $sid_name =  $kode_izin_sakit . "." . $request->file('sid')->getClientOriginalExtension();
                $destination_sid_path = "/public/uploads/sid";
                $sid = $sid_name;
                $data_sid = [
                    'doc_sid' => $sid,
                ];
            }

            $dataizinsakit = [
                'kode_izin_sakit' => $kode_izin_sakit,
                'nik' => $nik,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'id_user' => $user->id,
            ];

            $data = array_merge($dataizinsakit, $data_sid);
            $simpandatasakit = Izinsakit::create($data);
            if ($simpandatasakit) {
                if ($request->hasfile('sid')) {
                    $request->file('sid')->storeAs($destination_sid_path, $sid_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        $izinabsen = Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)
            ->join('karyawan', 'presensi_izinsakit.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['izinsakit'] = $izinabsen;
        return view('izinsakit.approve', $data);
    }

    public function storeapprove(Request $request, $kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        $izinsakit = Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)
            ->join('karyawan', 'presensi_izinsakit.nik', '=', 'karyawan.nik')
            ->first();
        $dari = $izinsakit->dari;
        $sampai = $izinsakit->sampai;
        $nik = $izinsakit->nik;
        $kode_dept = $izinsakit->kode_dept;
        $error = '';
        DB::beginTransaction();
        try {
            if (isset($request->approve)) {
                // echo 'test';


                Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->update([
                    'status' => 1
                ]);

                while (strtotime($dari) <= strtotime($sampai)) {

                    //Cek Jadwal Pada Setiap tanggal
                    $namahari = getnamaHari(date('D', strtotime($dari)));

                    $jamkerja = Setjamkerjabydate::join('presensi_jamkerja', 'presensi_jamkerja_bydate.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                        ->where('nik', $izinsakit->nik)
                        ->where('tanggal', $dari)
                        ->first();
                    if ($jamkerja == null) {
                        $jamkerja = Setjamkerjabyday::join('presensi_jamkerja', 'presensi_jamkerja_byday.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                            ->where('nik', $izinsakit->nik)->where('hari', $namahari)
                            ->first();
                    }

                    if ($jamkerja == null) {
                        $jamkerja = Detailsetjamkerjabydept::join('presensi_jamkerja_bydept', 'presensi_jamkerja_bydept_detail.kode_jk_dept', '=', 'presensi_jamkerja_bydept.kode_jk_dept')
                            ->join('presensi_jamkerja', 'presensi_jamkerja_bydept_detail.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                            ->where('kode_dept', $kode_dept)
                            ->where('kode_cabang', $izinsakit->kode_cabang)
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
                            'status' => 's',
                        ]);

                        Approveizinsakit::create([
                            'id_presensi' => $presensi->id,
                            'kode_izin_sakit' => $kode_izin_sakit,
                        ]);
                    }


                    $dari = date('Y-m-d', strtotime($dari . ' +1 day'));
                }
            } else {
                Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->update([
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


    public function cancelapprove($kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        $presensi = Approveizinsakit::where('kode_izin_sakit', $kode_izin_sakit)->get();
        try {
            Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->update([
                'status' => 0
            ]);
            Approveizinsakit::where('kode_izin_sakit', $kode_izin_sakit)->delete();
            Presensi::whereIn('id', $presensi->pluck('id_presensi'))->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function update(Request $request, $kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);

        $request->validate([
            'nik' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $izinsakit = Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->first();
            $data_sid = [];
            if ($request->hasfile('sid')) {
                $sid_name =  $kode_izin_sakit . "." . $request->file('sid')->getClientOriginalExtension();
                $destination_sid_path = "/public/uploads/sid";
                $sid = $sid_name;
                $data_sid = [
                    'doc_sid' => $sid,
                ];
            }

            $dataizinsakit = [
                'nik' => $request->nik,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,

            ];

            $data = array_merge($dataizinsakit, $data_sid);

            $simpandatasakit = Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->update($data);
            if ($simpandatasakit) {
                if ($request->hasfile('sid')) {
                    Storage::delete($destination_sid_path . "/" . $izinsakit->doc_sid);
                    $request->file('sid')->storeAs($destination_sid_path, $sid_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        try {
            Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        $izinabsen = Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)
            ->join('karyawan', 'presensi_izinsakit.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['izinsakit'] = $izinabsen;
        return view('izinsakit.show', $data);
    }
}

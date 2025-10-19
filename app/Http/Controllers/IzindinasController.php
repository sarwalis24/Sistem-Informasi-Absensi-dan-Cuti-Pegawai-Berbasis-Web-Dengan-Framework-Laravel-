<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Izindinas;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzindinasController extends Controller
{
    public function index(Request $request)
    {
        $qizin = Izindinas::query();
        $qizin->join('karyawan', 'presensi_izindinas.nik', '=', 'karyawan.nik');
        $qizin->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan');
        $qizin->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $qizin->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $qizin->whereBetween('presensi_izindinas.tanggal', [$request->dari, $request->sampai]);
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
            $qizin->where('presensi_izindinas.status', $request->status);
        }
        $qizin->orderBy('presensi_izindinas.status');
        $qizin->orderBy('presensi_izindinas.tanggal', 'desc');
        $izindinas = $qizin->paginate(15);
        $izindinas->appends($request->all());

        $data['izindinas'] = $izindinas;
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        return view('izindinas.index', $data);
    }

    public function create()
    {
        $user = User::where('id', '=', auth()->user()->id)->first();
        if ($user->hasRole('karyawan')) {
            return view('izindinas.create-mobile');
        }
        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();

        $data['karyawan'] = $karyawan;

        return view('izindinas.create', $data);
    }

    public function edit($kode_izin_dinas)
    {
        $user = User::where('id', '=', auth()->user()->id)->first();
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);

        $izindinas = Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->first();
        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();

        $data['karyawan'] = $karyawan;
        $data['izindinas'] = $izindinas;

        return view('izindinas.edit', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $role = $user->getRoleNames()->first();

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
            if ($jmlhari > 3) {
                return Redirect::back()->with(messageError('Tidak Boleh Lebih dari 3 Hari!'));
            }

            $cek_izin_dinas = Izindinas::where('nik', $nik)
                ->whereBetween('dari', [$request->dari, $request->sampai])
                ->orWhereBetween('sampai', [$request->dari, $request->sampai])->first();

            if ($cek_izin_dinas) {
                return Redirect::back()->with(messageError('Anda Sudah Mengajukan Izin Dinas Pada Rentang Tanggal Tersebut!'));
            }

            $lastizin = Izindinas::select('kode_izin_dinas')
                ->whereRaw('YEAR(dari)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('MONTH(dari)="' . date('m', strtotime($request->dari)) . '"')
                ->orderBy("kode_izin_dinas", "desc")
                ->first();
            $last_kode_izin = $lastizin != null ? $lastizin->kode_izin_dinas : '';
            $kode_izin_dinas  = buatkode($last_kode_izin, "ID"  . date('ym', strtotime($request->dari)), 4);

            Izindinas::create([
                'kode_izin_dinas' => $kode_izin_dinas,
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

    public function approve($kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        $izindinas = Izindinas::where('kode_izin_dinas', $kode_izin_dinas)
            ->join('karyawan', 'presensi_izindinas.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['izindinas'] = $izindinas;
        return view('izindinas.approve', $data);
    }

    public function storeapprove(Request $request, $kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        DB::beginTransaction();
        try {
            if (isset($request->approve)) {
                Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->update([
                    'status' => 1
                ]);
            } else {
                Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->update([
                    'status' => 2
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancelapprove($kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        try {
            Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->update([
                'status' => 0
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        try {
            Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function update(Request $request, $kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        $request->validate([
            'nik' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {
            Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->update([
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

    public function show($kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        $izindinas = Izindinas::where('kode_izin_dinas', $kode_izin_dinas)
            ->join('karyawan', 'presensi_izindinas.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['izindinas'] = $izindinas;
        return view('izindinas.show', $data);
    }
}

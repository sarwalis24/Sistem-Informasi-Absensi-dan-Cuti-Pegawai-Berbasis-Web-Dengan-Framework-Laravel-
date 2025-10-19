<?php

namespace App\Http\Controllers;

use App\Models\Detailpenyesuaiangaji;
use App\Models\Karyawan;
use App\Models\Penyesuaiangaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class PenyesuaiangajiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = !empty($request->tahun) ? $request->tahun : date('Y');
        $data['penyesuaiangaji'] = Penyesuaiangaji::where('tahun', $tahun)->orderBy('bulan')->get();
        $data['start_year'] = config('global.start_year');
        return view('payroll.penyesuaiangaji.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('payroll.penyesuaiangaji.create', $data);
    }


    public function edit($kode_penyesuaian_gaji)
    {
        $kode_penyesuaian_gaji = Crypt::decrypt($kode_penyesuaian_gaji);
        $data['penyesuaiangaji'] = Penyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)->first();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('payroll.penyesuaiangaji.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $bulan = $request->bulan > 9 ? $request->bulan : '0' . $request->bulan;

        $kode_penyesuaian_gaji = 'PYG' . $bulan . $request->tahun;
        try {
            $cek = Penyesuaiangaji::where('bulan', $request->bulan)->where('tahun', $request->tahun)->first();
            if ($cek) {
                return Redirect::back()->with(messageError('Data Sudah Ada'));
            }
            Penyesuaiangaji::create([
                'kode_penyesuaian_gaji' => $kode_penyesuaian_gaji,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Disimpan', $e->getMessage()));
        }
    }

    public function update(Request $request, $kode_penyesuaian_gaji)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        $kode_penyesuaian_gaji = Crypt::decrypt($kode_penyesuaian_gaji);
        $penyesuaiangaji = Penyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)->first();
        $bulan = $request->bulan > 9 ? $request->bulan : '0' . $request->bulan;
        $kode_penyesuaian_gaji_new = 'PYG' . $bulan . $request->tahun;
        try {
            $cek = Penyesuaiangaji::where('bulan', $request->bulan)->where('tahun', $request->tahun)
                ->where('kode_penyesuaian_gaji', '!=', $penyesuaiangaji->kode_penyesuaian_gaji)
                ->first();
            if ($cek) {
                return Redirect::back()->with(messageError('Data Sudah Ada'));
            }
            Penyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)->update([
                'kode_penyesuaian_gaji' => $kode_penyesuaian_gaji_new,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diubah'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Diubah', $e->getMessage()));
        }
    }


    public function setkaryawan($kode_penyesuaian_gaji)
    {
        $kode_penyesuaian_gaji = Crypt::decrypt($kode_penyesuaian_gaji);
        $data['penyesuaiangaji'] = Penyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)->first();
        $data['detailpenyesuaian'] = Detailpenyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)
            ->join('karyawan', 'karyawan_penyesuaian_gaji_detail.nik', '=', 'karyawan.nik')
            ->get();
        return view('payroll.penyesuaiangaji.setkaryawan', $data);
    }

    public function addkaryawan($kode_penyesuaian_gaji)
    {
        $data['kode_penyesuaian_gaji'] = Crypt::decrypt($kode_penyesuaian_gaji);
        $data['karyawan'] = Karyawan::orderby('nama_karyawan')->get();
        return view('payroll.penyesuaiangaji.addkaryawan', $data);
    }

    public function storekaryawan(Request $request, $kode_penyesuaian_gaji)
    {
        $kode_penyesuaian_gaji = Crypt::decrypt($kode_penyesuaian_gaji);
        $request->validate([
            'nik' => 'required',
            'penambah' => 'required',
            'pengurang' => 'required',
            'keterangan' => 'required',
        ]);

        try {
            $cek_karyawan = Detailpenyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)->where('nik', $request->nik)->first();
            if ($cek_karyawan) {
                return Redirect::back()->with(messageError('Data Karyawan Sudah Ada'));
            }
            Detailpenyesuaiangaji::create([
                'kode_penyesuaian_gaji' => $kode_penyesuaian_gaji,
                'nik' => $request->nik,
                'penambah' => toNumber($request->penambah),
                'pengurang' => toNumber($request->pengurang),
                'keterangan' => $request->keterangan
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Disimpan', $e->getMessage()));
        }
    }


    public function editkaryawan($kode_penyesuaian_gaji, $nik)
    {

        $kode_penyesuaian_gaji = Crypt::decrypt($kode_penyesuaian_gaji);
        $nik = Crypt::decrypt($nik);
        $data['karyawan'] = Karyawan::orderby('nama_karyawan')->get();
        $data['detailpenyesuaian'] = Detailpenyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)->where('nik', $nik)->first();
        return view('payroll.penyesuaiangaji.editkaryawan', $data);
    }


    public function updatekaryawan(Request $request, $kode_penyesuaian_gaji, $nik)
    {
        $kode_penyesuaian_gaji = Crypt::decrypt($kode_penyesuaian_gaji);
        $nik = Crypt::decrypt($nik);
        $request->validate([
            'penambah' => 'required',
            'pengurang' => 'required',
            'keterangan' => 'required',
        ]);

        try {
            Detailpenyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)->where('nik', $nik)
                ->update([
                    'penambah' => toNumber($request->penambah),
                    'pengurang' => toNumber($request->pengurang),
                    'keterangan' => $request->keterangan
                ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Disimpan', $e->getMessage()));
        }
    }


    public function destroykaryawan($kode_penyesuaian_gaji, $nik)
    {
        $kode_penyesuaian_gaji = Crypt::decrypt($kode_penyesuaian_gaji);
        $nik = Crypt::decrypt($nik);
        try {
            //code...
            Detailpenyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)->where('nik', $nik)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Dihapus ' . $e->getMessage()));
        }
    }

    public function destroy($kode_penyesuaian_gaji)
    {
        $kode_penyesuaian_gaji = Crypt::decrypt($kode_penyesuaian_gaji);
        try {
            //code...
            Penyesuaiangaji::where('kode_penyesuaian_gaji', $kode_penyesuaian_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Dihapus ' . $e->getMessage()));
        }
    }
}

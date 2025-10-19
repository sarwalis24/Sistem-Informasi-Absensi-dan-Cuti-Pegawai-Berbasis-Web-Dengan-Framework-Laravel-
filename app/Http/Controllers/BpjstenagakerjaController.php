<?php

namespace App\Http\Controllers;

use App\Models\Bpjstenagakerja;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class BpjstenagakerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = Bpjstenagakerja::query();
        $query->join('karyawan', 'karyawan_bpjstenagakerja.nik', '=', 'karyawan.nik');
        $query->select('karyawan_bpjstenagakerja.*', 'karyawan.nama_karyawan', 'karyawan.kode_dept', 'karyawan.kode_cabang');
        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $bpjstenagakerja = $query->paginate(20);
        $bpjstenagakerja->appends($request->all());
        $data['bpjstenagakerja'] = $bpjstenagakerja;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('datamaster.bpjstenagakerja.index', $data);
    }

    public function create()
    {
        $data['karyawan'] = Karyawan::orderby('nama_karyawan')->get();
        return view('datamaster.bpjstenagakerja.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'jumlah' => 'required',
            'tanggal_berlaku' => 'required'
        ]);

        //Kode Gaji = G250001;
        $tahun = date('Y', strtotime($request->tanggal_berlaku));
        $last_bpjs_tenagakerja = Bpjstenagakerja::orderBy('kode_bpjs_tk', 'desc')
            ->whereRaw('YEAR(tanggal_berlaku) = ' . $tahun)
            ->first();
        $last_kode_bpjs_tk = $last_bpjs_tenagakerja != null ? $last_bpjs_tenagakerja->kode_bpjs_tk : '';
        $kode_bpjs_tk = buatkode($last_kode_bpjs_tk, "K" . substr($tahun, 2, 2), 4);
        try {
            //code...
            Bpjstenagakerja::create([
                'kode_bpjs_tk' => $kode_bpjs_tk,
                'nik' => $request->nik,
                'jumlah' => toNumber($request->jumlah),
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Disimpan ' . $e->getMessage()));
        }
    }


    public function edit($kode_bpjs_tk)
    {
        $kode_bpjs_tk = Crypt::decrypt($kode_bpjs_tk);
        $data['karyawan'] = Karyawan::orderby('nama_karyawan')->get();
        $data['bpjstenagakerja'] = Bpjstenagakerja::where('kode_bpjs_tk', $kode_bpjs_tk)->first();
        return view('datamaster.bpjstenagakerja.edit', $data);
    }

    public function update(Request $request, $kode_bpjs_tk)
    {
        $kode_bpjs_tk = Crypt::decrypt($kode_bpjs_tk);
        $request->validate([
            'jumlah' => 'required',
            'tanggal_berlaku' => 'required'
        ]);
        try {
            Bpjstenagakerja::where('kode_bpjs_tk', $kode_bpjs_tk)->update([
                'jumlah' => toNumber($request->jumlah),
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Diupdate ' . $e->getMessage()));
        }
    }

    public function destroy($kode_bpjs_tk)
    {
        $kode_bpjs_tk = Crypt::decrypt($kode_bpjs_tk);
        try {
            bpjstenagakerja::where('kode_bpjs_tk', $kode_bpjs_tk)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Dihapus ' . $e->getMessage()));
        }
    }
}

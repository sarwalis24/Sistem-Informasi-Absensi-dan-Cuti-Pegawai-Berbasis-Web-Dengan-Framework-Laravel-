<?php

namespace App\Http\Controllers;

use App\Models\Bpjskesehatan;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class BpjskesehatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Bpjskesehatan::query();
        $query->join('karyawan', 'karyawan_bpjskesehatan.nik', '=', 'karyawan.nik');
        $query->select('karyawan_bpjskesehatan.*', 'karyawan.nama_karyawan', 'karyawan.kode_dept', 'karyawan.kode_cabang');
        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $bpjskesehatan = $query->paginate(20);
        $bpjskesehatan->appends($request->all());
        $data['bpjskesehatan'] = $bpjskesehatan;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('datamaster.bpjskesehatan.index', $data);
    }

    public function create()
    {
        $data['karyawan'] = Karyawan::orderby('nama_karyawan')->get();
        return view('datamaster.bpjskesehatan.create', $data);
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
        $last_bpjs_kesehatan = Bpjskesehatan::orderBy('kode_bpjs_kesehatan', 'desc')
            ->whereRaw('YEAR(tanggal_berlaku) = ' . $tahun)
            ->first();
        $last_kode_bpjs_kesehatan = $last_bpjs_kesehatan != null ? $last_bpjs_kesehatan->kode_bpjs_kesehatan : '';
        $kode_bpjs_kesehatan = buatkode($last_kode_bpjs_kesehatan, "K" . substr($tahun, 2, 2), 4);
        try {
            //code...
            Bpjskesehatan::create([
                'kode_bpjs_kesehatan' => $kode_bpjs_kesehatan,
                'nik' => $request->nik,
                'jumlah' => toNumber($request->jumlah),
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Disimpan ' . $e->getMessage()));
        }
    }


    public function edit($kode_bpjs_kesehatan)
    {
        $kode_bpjs_kesehatan = Crypt::decrypt($kode_bpjs_kesehatan);
        $data['karyawan'] = Karyawan::orderby('nama_karyawan')->get();
        $data['bpjskesehatan'] = Bpjskesehatan::where('kode_bpjs_kesehatan', $kode_bpjs_kesehatan)->first();
        return view('datamaster.bpjskesehatan.edit', $data);
    }

    public function update(Request $request, $kode_bpjs_kesehatan)
    {
        $kode_bpjs_kesehatan = Crypt::decrypt($kode_bpjs_kesehatan);
        $request->validate([
            'jumlah' => 'required',
            'tanggal_berlaku' => 'required'
        ]);
        try {
            Bpjskesehatan::where('kode_bpjs_kesehatan', $kode_bpjs_kesehatan)->update([
                'jumlah' => toNumber($request->jumlah),
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Diupdate ' . $e->getMessage()));
        }
    }

    public function destroy($kode_bpjs_kesehatan)
    {
        $kode_bpjs_kesehatan = Crypt::decrypt($kode_bpjs_kesehatan);
        try {
            bpjskesehatan::where('kode_bpjs_kesehatan', $kode_bpjs_kesehatan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Dihapus ' . $e->getMessage()));
        }
    }
}

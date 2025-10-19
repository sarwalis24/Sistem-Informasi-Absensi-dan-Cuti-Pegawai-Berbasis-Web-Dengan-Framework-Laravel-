<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailtunjangan;
use App\Models\Jenistunjangan;
use App\Models\Karyawan;
use App\Models\Tunjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TunjanganController extends Controller
{
    public function index(Request $request)
    {

        $jenis_tunjangan = Jenistunjangan::orderBy('kode_jenis_tunjangan')->get();
        $select_tunjangan = [];
        foreach ($jenis_tunjangan as $d) {
            $select_tunjangan[] = DB::raw('SUM(IF(karyawan_tunjangan_detail.kode_jenis_tunjangan = "' . $d->kode_jenis_tunjangan . '", karyawan_tunjangan_detail.jumlah, 0)) as jumlah_' . $d->kode_jenis_tunjangan);
        }
        $query = Detailtunjangan::query();
        $query->join('karyawan_tunjangan', 'karyawan_tunjangan_detail.kode_tunjangan', '=', 'karyawan_tunjangan.kode_tunjangan');
        $query->join('karyawan', 'karyawan_tunjangan.nik', '=', 'karyawan.nik');
        $query->select(
            'karyawan_tunjangan_detail.kode_tunjangan',
            'karyawan_tunjangan.nik',
            'karyawan.nama_karyawan',
            'karyawan.kode_dept',
            'karyawan.kode_cabang',
            'tanggal_berlaku',
            ...$select_tunjangan
        );
        if (!empty($request->nama_karyawan)) {
            $query->where('karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $query->groupBy(
            'karyawan_tunjangan_detail.kode_tunjangan',
            'karyawan_tunjangan.nik',
            'karyawan.nama_karyawan',
            'karyawan.kode_dept',
            'karyawan.kode_cabang',
            'tanggal_berlaku'
        );
        // dd($query->get());
        $tunjangan = $query->paginate(20);
        $tunjangan->appends($request->all());
        $data['tunjangan'] = $tunjangan;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['jenis_tunjangan'] = $jenis_tunjangan;
        return view('datamaster.tunjangan.index', $data);
    }


    public function create()
    {
        $data['karyawan'] = Karyawan::orderby('nama_karyawan')->get();
        $data['jenis_tunjangan'] = Jenistunjangan::orderBy('kode_jenis_tunjangan')->get();
        return view('datamaster.tunjangan.create', $data);
    }


    public function edit($kode_tunjangan)
    {
        $kode_tunjangan = Crypt::decrypt($kode_tunjangan);
        $data['karyawan'] = Karyawan::orderby('nama_karyawan')->get();
        $data['tunjangan'] = Tunjangan::where('kode_tunjangan', $kode_tunjangan)->first();
        $detail_tunjangan = Jenistunjangan::where('kode_tunjangan', $kode_tunjangan)
            ->leftJoin('karyawan_tunjangan_detail', 'karyawan_tunjangan_detail.kode_jenis_tunjangan', '=', 'jenis_tunjangan.kode_jenis_tunjangan')
            ->orderBy('jenis_tunjangan.kode_jenis_tunjangan')
            ->get();
        $data['jenis_tunjangan'] = $detail_tunjangan;
        return view('datamaster.tunjangan.edit', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'kode_jenis_tunjangan' => 'required',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required',
            'tanggal_berlaku' => 'required',
        ]);

        //Kode Tunjangan = T250001;
        $tahun_gaji = date('Y', strtotime($request->tanggal_berlaku));
        $last_tunjangan = Tunjangan::orderBy('kode_tunjangan', 'desc')
            ->whereRaw('YEAR(tanggal_berlaku) = ' . $tahun_gaji)
            ->first();
        $last_kode_tunjangan = $last_tunjangan != null ? $last_tunjangan->kode_tunjangan : '';
        $kode_tunjangan = buatkode($last_kode_tunjangan, "T" . substr($tahun_gaji, 2, 2), 4);
        DB::beginTransaction();
        try {
            //code...
            Tunjangan::create([
                'kode_tunjangan' => $kode_tunjangan,
                'nik' => $request->nik,
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);

            foreach ($request->kode_jenis_tunjangan as $key => $value) {
                Detailtunjangan::create([
                    'kode_tunjangan' => $kode_tunjangan,
                    'kode_jenis_tunjangan' => $value,
                    'jumlah' => toNumber($request->jumlah[$key]),
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError('Data Gagal Disimpan ' . $e->getMessage()));
        }
    }


    public function update($kode_tunjangan, Request $request)
    {
        $kode_tunjangan = Crypt::decrypt($kode_tunjangan);
        $request->validate([
            'kode_jenis_tunjangan' => 'required',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required',
            'tanggal_berlaku' => 'required',
        ]);
        DB::beginTransaction();
        try {
            //code...
            Tunjangan::where('kode_tunjangan', $kode_tunjangan)->update([
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);

            Detailtunjangan::where('kode_tunjangan', $kode_tunjangan)->delete();
            foreach ($request->kode_jenis_tunjangan as $key => $value) {
                Detailtunjangan::create([
                    'kode_tunjangan' => $kode_tunjangan,
                    'kode_jenis_tunjangan' => $value,
                    'jumlah' => toNumber($request->jumlah[$key]),
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError('Data Gagal Disimpan ' . $e->getMessage()));
        }
    }


    public function destroy($kode_tunjangan)
    {
        $kode_tunjangan = Crypt::decrypt($kode_tunjangan);
        try {
            Tunjangan::where('kode_tunjangan', $kode_tunjangan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Dihapus ' . $e->getMessage()));
        }
    }
}

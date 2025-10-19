<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailsetjamkerjabydept;
use App\Models\Jamkerja;
use App\Models\Setjamkerjabydept;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JamkerjabydeptController extends Controller
{
    public function index(Request $request)
    {
        $query = Setjamkerjabydept::query();
        $query->join('cabang', 'presensi_jamkerja_bydept.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('departemen', 'presensi_jamkerja_bydept.kode_dept', '=', 'departemen.kode_dept');
        $query->select('presensi_jamkerja_bydept.*', 'cabang.nama_cabang', 'departemen.nama_dept');

        if (!empty($request->kode_cabang)) {
            $query->where('presensi_jamkerja_bydept.kode_cabang', $request->kode_cabang);
        }
        $data['jamkerjabydept'] = $query->paginate(15);
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('jamkerjabydept.index', $data);
    }

    public function create()
    {
        $data['cabang'] = Cabang::orderby('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['jamkerja'] = Jamkerja::orderBy('kode_jam_kerja')->get();
        return view('jamkerjabydept.create', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
        ]);
        $kode_cabang = $request->kode_cabang;
        $kode_dept = $request->kode_dept;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        // dd($kode_jam_kerja);
        $cekjamkerjabydept = Setjamkerjabydept::where('kode_cabang', $kode_cabang)
            ->where('kode_dept', $kode_dept)
            ->first();

        if ($cekjamkerjabydept) {
            return Redirect::back()->with(messageError('Data Jam Kerja Sudah Ada'));
        }
        DB::beginTransaction();
        try {

            Setjamkerjabydept::create([
                'kode_jk_dept' => 'J' . $kode_cabang . $kode_dept,
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept
            ]);

            for ($i = 0; $i < count($hari); $i++) {
                if (!empty($kode_jam_kerja[$i])) {
                    Detailsetjamkerjabydept::create([
                        'kode_jk_dept' => 'J' . $kode_cabang . $kode_dept,
                        'hari' => $hari[$i],
                        'kode_jam_kerja' => $kode_jam_kerja[$i]
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_jk_dept)
    {
        $kode_jk_dept = Crypt::decrypt($kode_jk_dept);

        $data['jamkerjabydept'] = Setjamkerjabydept::where('kode_jk_dept', $kode_jk_dept)
            ->join('cabang', 'presensi_jamkerja_bydept.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('departemen', 'presensi_jamkerja_bydept.kode_dept', '=', 'departemen.kode_dept')
            ->first();
        $data['jamkerja'] = Jamkerja::orderBy('kode_jam_kerja')->get();
        $data['detailjamkerjabydept'] = Detailsetjamkerjabydept::where('kode_jk_dept', $kode_jk_dept)->pluck('kode_jam_kerja', 'hari')->toArray();
        return view('jamkerjabydept.edit', $data);
    }

    public function update($kode_jk_dept, Request $request)
    {
        $kode_jk_dept = Crypt::decrypt($kode_jk_dept);
        $jamkerjabydept = Setjamkerjabydept::where('kode_jk_dept', $kode_jk_dept)->first();
        $kode_cabang = $jamkerjabydept->kode_cabang;
        $kode_dept = $jamkerjabydept->kode_dept;

        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;

        DB::beginTransaction();
        try {

            Detailsetjamkerjabydept::where('kode_jk_dept', $kode_jk_dept)->delete();
            for ($i = 0; $i < count($hari); $i++) {
                if (!empty($kode_jam_kerja[$i])) {
                    Detailsetjamkerjabydept::create([
                        'kode_jk_dept' => 'J' . $kode_cabang . $kode_dept,
                        'hari' => $hari[$i],
                        'kode_jam_kerja' => $kode_jam_kerja[$i]
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_jk_dept)
    {
        $kode_jk_dept = Crypt::decrypt($kode_jk_dept);
        try {
            Setjamkerjabydept::where('kode_jk_dept', $kode_jk_dept)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

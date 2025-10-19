<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $data['cuti'] = Cuti::orderBy('kode_cuti')->get();
        return view('datamaster.cuti.index', $data);
    }

    public function create()
    {
        return view('datamaster.cuti.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cuti' => 'required|max:3|unique:cuti,kode_cuti',
            'jenis_cuti' => 'required',
            'jumlah_hari' => 'required',
        ]);


        try {
            Cuti::create([
                'kode_cuti' => $request->kode_cuti,
                'jenis_cuti' => $request->jenis_cuti,
                'jumlah_hari' => $request->jumlah_hari,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_cuti)
    {
        $kode_cuti = Crypt::decrypt($kode_cuti);
        $data['cuti'] = Cuti::where('kode_cuti', $kode_cuti)->first();
        return view('datamaster.cuti.edit', $data);
    }

    public function update(Request $request, $kode_cuti)
    {
        $kode_cuti = Crypt::decrypt($kode_cuti);
        $request->validate([
            'jenis_cuti' => 'required',
            'jumlah_hari' => 'required',
        ]);

        try {
            Cuti::where('kode_cuti', $kode_cuti)->update([
                'jenis_cuti' => $request->jenis_cuti,
                'jumlah_hari' => $request->jumlah_hari,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    function destroy($kode_cuti)
    {
        $kode_cuti = Crypt::decrypt($kode_cuti);
        try {
            Cuti::where('kode_cuti', $kode_cuti)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

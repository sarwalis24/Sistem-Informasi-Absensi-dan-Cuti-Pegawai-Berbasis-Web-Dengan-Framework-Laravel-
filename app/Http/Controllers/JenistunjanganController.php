<?php

namespace App\Http\Controllers;

use App\Models\Jenistunjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class JenistunjanganController extends Controller
{
    public function index()
    {
        $data['jenistunjangan'] = Jenistunjangan::all();
        return view('datamaster.jenistunjangan.index', $data);
    }

    public function create()
    {
        return view('datamaster.jenistunjangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jenis_tunjangan' => 'required|max:4|unique:jenis_tunjangan,kode_jenis_tunjangan',
            'jenis_tunjangan' => 'required',
        ]);

        try {
            Jenistunjangan::create([
                'kode_jenis_tunjangan' => $request->kode_jenis_tunjangan,
                'jenis_tunjangan' => $request->jenis_tunjangan
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_jenis_tunjangan)
    {
        $kode_jenis_tunjangan = Crypt::decrypt($kode_jenis_tunjangan);
        $data['jenistunjangan'] = Jenistunjangan::where('kode_jenis_tunjangan', $kode_jenis_tunjangan)->first();
        return view('datamaster.jenistunjangan.edit', $data);
    }


    public function update(Request $request, $kode_jenis_tunjangan)
    {
        $kode_jenis_tunjangan = Crypt::decrypt($kode_jenis_tunjangan);
        $request->validate([
            'kode_jenis_tunjangan' => 'required|max:4|unique:jenis_tunjangan,kode_jenis_tunjangan,' . $kode_jenis_tunjangan . ',kode_jenis_tunjangan',
            'jenis_tunjangan' => 'required',
        ]);
        try {
            Jenistunjangan::where('kode_jenis_tunjangan', $kode_jenis_tunjangan)->update([
                'kode_jenis_tunjangan' => $request->kode_jenis_tunjangan,
                'jenis_tunjangan' => $request->jenis_tunjangan
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_jenis_tunjangan)
    {
        $kode_jenis_tunjangan = Crypt::decrypt($kode_jenis_tunjangan);
        try {
            Jenistunjangan::where('kode_jenis_tunjangan', $kode_jenis_tunjangan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

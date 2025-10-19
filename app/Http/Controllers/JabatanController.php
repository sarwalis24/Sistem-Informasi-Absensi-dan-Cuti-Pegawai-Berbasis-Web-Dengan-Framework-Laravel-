<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::query();
        $data['jabatan'] = $query->get();
        return view('datamaster.jabatan.index', $data);
    }

    public function create()
    {
        return view('datamaster.jabatan.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'kode_jabatan' => 'required',
            'nama_jabatan' => 'required'
        ]);
        try {
            //Simpan Data Jabatan
            Jabatan::create([
                'kode_jabatan' => $request->kode_jabatan,
                'nama_jabatan' => $request->nama_jabatan
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_jabatan)
    {
        $kode_jabatan = Crypt::decrypt($kode_jabatan);
        $data['jabatan'] = Jabatan::where('kode_jabatan', $kode_jabatan)->first();
        return view('datamaster.jabatan.edit', $data);
    }

    public function update($kode_jabatan, Request $request)
    {
        $kode_jabatan = Crypt::decrypt($kode_jabatan);

        $request->validate([
            'kode_jabatan' => 'required',
            'nama_jabatan' => 'required'
        ]);
        try {
            //Simpan Data Jabatan
            Jabatan::where('kode_jabatan', $kode_jabatan)->update([
                'kode_jabatan' => $request->kode_jabatan,
                'nama_jabatan' => $request->nama_jabatan
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_jabatan)
    {
        $kode_jabatan = Crypt::decrypt($kode_jabatan);
        try {
            Jabatan::where('kode_jabatan', $kode_jabatan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

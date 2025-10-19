<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class DepartemenController extends Controller
{

    public function index(Request $request)
    {
        $query = Departemen::query();
        $data['departemen'] = $query->get();
        return view('datamaster.departemen.index', $data);
    }

    public function create()
    {
        return view('datamaster.departemen.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'kode_dept' => 'required',
            'nama_dept' => 'required'
        ]);
        try {
            //Simpan Data Departemen
            Departemen::create([
                'kode_dept' => $request->kode_dept,
                'nama_dept' => $request->nama_dept
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_dept)
    {
        $kode_dept = Crypt::decrypt($kode_dept);
        $data['departemen'] = Departemen::where('kode_dept', $kode_dept)->first();
        return view('datamaster.departemen.edit', $data);
    }

    public function update($kode_dept, Request $request)
    {
        $kode_dept = Crypt::decrypt($kode_dept);

        $request->validate([
            'kode_dept' => 'required',
            'nama_dept' => 'required'
        ]);
        try {
            //Simpan Data Departemen
            Departemen::where('kode_dept', $kode_dept)->update([
                'kode_dept' => $request->kode_dept,
                'nama_dept' => $request->nama_dept
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_dept)
    {
        $kode_dept = Crypt::decrypt($kode_dept);
        try {
            Departemen::where('kode_dept', $kode_dept)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

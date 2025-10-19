<?php

namespace App\Http\Controllers;

use App\Models\Jamkerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class JamkerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = Jamkerja::query();
        if (!empty($request->nama_jam_kerja_search)) {
            $query->where('nama_jam_kerja', 'like', '%' . $request->nama_jam_kerja_search . '%');
        }
        $data['jamkerja'] = $query->get();

        return view('datamaster.jamkerja.index', $data);
    }

    public function create()
    {
        return view('datamaster.jamkerja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jam_kerja' => 'required',
            'nama_jam_kerja' => 'required',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'istirahat' => 'required',
            'lintashari' => 'required',
            'total_jam' => 'required',
            'jam_awal_istirahat' => 'required_if:istirahat,1',
            'jam_akhir_istirahat' => 'required_if:istirahat,1',
        ]);

        try {
            Jamkerja::create([
                'kode_jam_kerja' => $request->kode_jam_kerja,
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'jam_masuk' => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'istirahat' => $request->istirahat,
                'lintashari' => $request->lintashari,
                'total_jam' => $request->total_jam,
                'jam_awal_istirahat' => $request->jam_awal_istirahat,
                'jam_akhir_istirahat' => $request->jam_akhir_istirahat,
                'keterangan' => $request->keterangan
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_jam_kerja)
    {
        $kode_jam_kerja = Crypt::decrypt($kode_jam_kerja);
        $data['jamkerja'] = Jamkerja::where('kode_jam_kerja', $kode_jam_kerja)->first();
        //dd($data['jamkerja']);
        return view('datamaster.jamkerja.edit', $data);
    }


    public function update(Request $request, $kode_jam_kerja)
    {
        $kode_jam_kerja = Crypt::decrypt($kode_jam_kerja);
        $request->validate([
            'kode_jam_kerja' => 'required',
            'nama_jam_kerja' => 'required',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'istirahat' => 'required',
            'lintashari' => 'required',
            'total_jam' => 'required',
            'jam_awal_istirahat' => 'required_if:istirahat,1',
            'jam_akhir_istirahat' => 'required_if:istirahat,1',
        ]);

        try {
            $jamkerja = Jamkerja::find($kode_jam_kerja);
            $jamkerja->update([
                'kode_jam_kerja' => $request->kode_jam_kerja,
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'jam_masuk' => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'istirahat' => $request->istirahat,
                'lintashari' => $request->lintashari,
                'total_jam' => $request->total_jam,
                'jam_awal_istirahat' => $request->jam_awal_istirahat,
                'jam_akhir_istirahat' => $request->jam_akhir_istirahat,
                'keterangan' => $request->keterangan
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_jam_kerja)
    {
        $kode_jam_kerja = Crypt::decrypt($kode_jam_kerja);
        try {
            Jamkerja::where('kode_jam_kerja', $kode_jam_kerja)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

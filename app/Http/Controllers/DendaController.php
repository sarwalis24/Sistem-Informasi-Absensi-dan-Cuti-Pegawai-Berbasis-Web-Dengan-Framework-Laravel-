<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class DendaController extends Controller
{

    public function index()
    {
        $data['denda'] = Denda::orderBy('id')->get();
        return view('denda.index', $data);
    }

    public function create()
    {
        return view('denda.create');
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['denda'] = Denda::findorFail($id);
        return view('denda.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $denda = Denda::findorFail($id);

        $request->validate([
            'dari' => 'required|numeric',
            'sampai' => 'required|numeric',
            'denda' => 'required'
        ]);

        try {
            $denda->update([
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'denda' => toNumber($request->denda)
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Diupdate ' . $e->getMessage()));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'dari' => 'required|numeric',
            'sampai' => 'required|numeric',
            'denda' => 'required'
        ]);

        try {
            Denda::create([
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'denda' => toNumber($request->denda)
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Disimpan ' . $e->getMessage()));
        }
    }


    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            $denda = Denda::findorfail($id);
            $denda->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data Gagal Dihapus ' . $e->getMessage()));
        }
    }
}

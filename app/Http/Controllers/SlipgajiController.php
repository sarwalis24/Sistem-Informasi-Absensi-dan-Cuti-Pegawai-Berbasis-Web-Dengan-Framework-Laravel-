<?php

namespace App\Http\Controllers;

use App\Models\Slipgaji;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class SlipgajiController extends Controller
{
    public function index()
    {
        $user = User::where('id', auth()->user()->id)->first();

        $data['start_year'] = config('global.start_year');
        if ($user->hasRole('karyawan')) {
            $data['slipgaji'] = Slipgaji::orderBy('tahun')
                ->orderBy('bulan')
                ->where('status', '1')
                ->get();
            return view('payroll.slipgaji.index_mobile', $data);
        } else {
            $data['slipgaji'] = Slipgaji::orderBy('tahun')->orderBy('bulan')->get();
            return view('payroll.slipgaji.index', $data);
        }
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('payroll.slipgaji.create', $data);
    }

    public function store(Request $request)
    {

        try {
            Slipgaji::create([
                'kode_slip_gaji' => 'GJ' . $request->bulan . $request->tahun,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status' => $request->status
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);
        $data['slipgaji'] = Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->first();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('payroll.slipgaji.edit', $data);
    }

    public function update(Request $request, $kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);
        try {
            Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->update([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status' => $request->status
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_slip_gaji)
    {
        $kode_slip_gaji = Crypt::decrypt($kode_slip_gaji);
        try {
            Slipgaji::where('kode_slip_gaji', $kode_slip_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pengaturanumum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GeneralsettingController extends Controller
{
    public function index()
    {
        $data['setting'] = Pengaturanumum::where('id', 1)->first();
        return view('generalsettings.index', $data);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'nama_perusahaan' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'total_jam_bulan' => 'required',
            'periode_laporan_dari' => 'required',
            'periode_laporan_sampai' => 'required',
            'domain_email' => 'required|regex:/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/',

        ]);

        try {
            //dd($request->denda);
            DB::beginTransaction();
            $setting = Pengaturanumum::findOrFail($id);

            $data = [
                'nama_perusahaan' => $request->nama_perusahaan,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'total_jam_bulan' => $request->total_jam_bulan,
                'denda' => $request->has('denda') ? true : false,
                'face_recognition' => $request->has('face_recognition') ? true : false,
                'periode_laporan_dari' => $request->periode_laporan_dari,
                'periode_laporan_sampai' => $request->periode_laporan_sampai,
                'periode_laporan_next_bulan' => $request->has('periode_laporan_next_bulan') ? true : false,
                'batasi_absen' => $request->has('batasi_absen') ? true : false,
                'multi_lokasi' => $request->has('multi_lokasi') ? true : false,
                'batas_jam_absen' => $request->batas_jam_absen,
                'batas_jam_absen_pulang' => $request->batas_jam_absen_pulang,
                'cloud_id' => $request->cloud_id,
                'api_key' => $request->api_key,
                'domain_email' => $request->domain_email,
                'domain_wa_gateway' => $request->domain_wa_gateway,
                'wa_api_key' => $request->wa_api_key,
                'notifikasi_wa' => $request->has('notifikasi_wa') ? true : false,
                'batasi_hari_izin' => $request->has('batasi_hari_izin') ? true : false,
                'jml_hari_izin_max' => $request->jml_hari_izin_max,
                'batas_presensi_lintashari' => $request->batas_presensi_lintashari,
            ];

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '.' . $logo->getClientOriginalExtension();
                $logo->storeAs('public/logo', $logoName);

                // Hapus logo lama jika ada
                if ($setting->logo && Storage::exists('public/logo/' . $setting->logo)) {
                    Storage::delete('public/logo/' . $setting->logo);
                }

                $data['logo'] = $logoName;
            }

            $setting->update($data);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

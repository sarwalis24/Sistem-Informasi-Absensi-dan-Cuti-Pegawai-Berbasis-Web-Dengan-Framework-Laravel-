<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Facerecognition;
use App\Models\Jamkerja;
use App\Models\Karyawan;
use App\Models\Pengaturanumum;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiistirahatController extends Controller
{
    public function create()
    {
        //Get Data Karyawan By User
        $user = User::where('id', auth()->user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $karyawan = Karyawan::where('nik', $userkaryawan->nik)->first();

        //Cek Lokasi Kantor
        $lokasi_kantor = Cabang::where('kode_cabang', $karyawan->kode_cabang)->first();

        //Cek Lintas Hari
        $hariini = date("Y-m-d");
        $jamsekarang = date("H:i");

        //Cek Presensi
        $presensi = Presensi::where('nik', $karyawan->nik)->where('tanggal', $hariini)->first();

        if (!$presensi) {
            return Redirect::back()->with(messageError('Anda belum melakukan presensi masuk'));
        }

        $jamkerja = Jamkerja::where('kode_jam_kerja', $presensi->kode_jam_kerja)->first();
        if ($jamkerja->istirahat == 0) {
            return Redirect::back()->with(messageError('Tidak Ada Istirhat Untuk Jam Kerja Saat Ini'));
        }

        $tgl_sebelumnya = date('Y-m-d', strtotime("-1 days", strtotime($hariini)));
        $cekpresensi_sebelumnya = Presensi::join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('tanggal', $tgl_sebelumnya)
            ->where('nik', $karyawan->nik)
            ->first();

        $ceklintashari_presensi = $cekpresensi_sebelumnya != null  ? $cekpresensi_sebelumnya->lintashari : 0;

        if ($ceklintashari_presensi == 1) {
            if ($jamsekarang < "08:00") {
                $hariini = $tgl_sebelumnya;
            }
        }

        $namahari = getnamaHari(date('D', strtotime($hariini)));

        $kode_dept = $karyawan->kode_dept;




        $data['hariini'] = $hariini;
        $data['jam_kerja'] = $jamkerja;
        $data['lokasi_kantor'] = $lokasi_kantor;
        $data['presensi'] = $presensi;
        $data['karyawan'] = $karyawan;
        $data['wajah'] = Facerecognition::where('nik', $karyawan->nik)->count();

        return view('presensiistirahat.create', $data);
    }



    public function store(Request $request)
    {
        $generalsetting = Pengaturanumum::where('id', 1)->first();
        $user = User::where('id', auth()->user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $karyawan = Karyawan::where('nik', $userkaryawan->nik)->first();
        $status_lock_location = $karyawan->lock_location;

        $status = $request->status;
        $lokasi = $request->lokasi;
        $kode_jam_kerja = $request->kode_jam_kerja;



        $tanggal_sekarang = date("Y-m-d");
        $jam_sekarang = date("H:i");

        $tanggal_kemarin = date("Y-m-d", strtotime("-1 days"));

        $tanggal_besok = date("Y-m-d", strtotime("+1 days"));

        //Cek Presensi Kemarin
        $presensi_kemarin = Presensi::where('nik', $karyawan->nik)
            ->join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('nik', $karyawan->nik)
            ->where('tanggal', $tanggal_kemarin)->first();

        $lintas_hari = $presensi_kemarin ? $presensi_kemarin->lintashari : 0;

        //Jika Presensi Kemarin Status Lintas Hari nya 1 Makan Tanggal Presensi Sekarang adalah Tanggal Kemarin
        $tanggal_presensi = $lintas_hari == 1 ? $tanggal_kemarin : $tanggal_sekarang;

        //Get Lokasi User
        $koordinat_user = explode(",", $lokasi);
        $latitude_user = $koordinat_user[0];
        $longitude_user = $koordinat_user[1];

        //Get Lokasi Kantor
        $cabang = Cabang::where('kode_cabang', $karyawan->kode_cabang)->first();
        // $lokasi_kantor = $cabang->lokasi_cabang;
        $lokasi_kantor = $request->lokasi_cabang;



        $koordinat_kantor = explode(",", $lokasi_kantor);
        $latitude_kantor = $koordinat_kantor[0];
        $longitude_kantor = $koordinat_kantor[1];

        $jarak = hitungjarak($latitude_kantor, $longitude_kantor, $latitude_user, $longitude_user);


        $radius = round($jarak["meters"]);

        $tanggal_selesai_istirahat = $lintas_hari == 1 ? $tanggal_besok : $tanggal_sekarang;

        $in_out = $status == 1 ? "in" : "out";
        $image = $request->image;
        $folderPath = "public/uploads/istirahat/";
        $formatName = $karyawan->nik . "-" . $tanggal_presensi . "-" . $in_out;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        $jam_kerja = Jamkerja::where('kode_jam_kerja', $kode_jam_kerja)->first();

        $jam_presensi = $tanggal_sekarang . " " . $jam_sekarang;


        $batas_jam_absen = 30;

        $jam_awal_istirahat = $tanggal_presensi . " " . date('H:i', strtotime($jam_kerja->jam_awal_istirahat));
        //Jam Mulai Istirahat 30 Menit Sebelum Jam Istirahat
        $jam_mulai_istirahat = $tanggal_presensi . " " . date('H:i', strtotime('-' . $batas_jam_absen . ' minutes', strtotime($jam_awal_istirahat)));

        //Jam Selesai Istirahat adalah 30 Menit Setelah Jam Istirahat
        $jam_mulai_akhir_istirahat =  date('Y-m-d H:i', strtotime('+' . $batas_jam_absen . ' minutes', strtotime($jam_awal_istirahat)));
        //return $jam_mulai_pulang;
        $jam_selesai_istirahat = $tanggal_selesai_istirahat . " " . $jam_kerja->jam_akhir_istirahat;

        $presensi_hariini = Presensi::where('nik', $karyawan->nik)
            ->where('tanggal', $tanggal_presensi)
            ->first();


        if ($status_lock_location == 1 && $radius > $cabang->radius_cabang) {
            return response()->json(['status' => false, 'message' => 'Anda Berada Di Luar Radius Kantor, Jarak Anda ' . formatAngka($radius) . ' Meters Dari Kantor', 'notifikasi' => 'notifikasi_radius'], 400);
        } else {
            if ($status == 1) {
                if ($presensi_hariini && $presensi_hariini->istirahat_in != null) {
                    return response()->json(['status' => false, 'message' => 'Anda Sudah Absen Masuk Hari Ini', 'notifikasi' => 'notifikasi_sudahabsen'], 400);
                } else if ($jam_presensi < $jam_mulai_istirahat) {
                    return response()->json(['status' => false, 'message' => 'Maaf Belum Waktunya Memulai Istirahat, Waktu Istirahat Dimulai Pukul ' . formatIndo3($jam_mulai_istirahat), 'notifikasi' => 'notifikasi_mulaiabsen'], 400);
                } else {
                    try {
                        if ($presensi_hariini != null) {
                            Presensi::where('id', $presensi_hariini->id)->update([
                                'istirahat_in' => $jam_presensi,
                                'lokasi_istirahat_in' => $lokasi,
                                'foto_istirahat_in' => $fileName
                            ]);
                        }
                        Storage::put($file, $image_base64);

                        return response()->json(['status' => true, 'message' => 'Berhasil Memulai Istirahat', 'notifikasi' => ''], 200);
                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
                    }
                }
            } else {
                if ($presensi_hariini && $presensi_hariini->istirahat_out != null) {
                    return response()->json(['status' => false, 'message' => 'Anda Sudah Mengakhiri Istirahat Hari Ini', 'notifikasi' => ''], 400);
                } else {
                    try {
                        if ($presensi_hariini != null) {
                            Presensi::where('id', $presensi_hariini->id)->update([
                                'istirahat_out' => $jam_presensi,
                                'lokasi_istirahat_out' => $lokasi,
                                'foto_istirahat_out' => $fileName
                            ]);
                        }
                        Storage::put($file, $image_base64);
                        return response()->json(['status' => true, 'message' => 'Berhasil Mengakhiri Istirahat', 'notifikasi' => ''], 200);
                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
                    }
                }
            }
        }
    }
}

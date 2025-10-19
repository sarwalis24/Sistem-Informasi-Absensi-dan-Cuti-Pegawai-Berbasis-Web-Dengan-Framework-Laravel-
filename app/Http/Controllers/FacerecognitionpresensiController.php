<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Denda;
use App\Models\Detailharilibur;
use App\Models\Detailsetjamkerjabydept;
use App\Models\Facerecognition;
use App\Models\Harilibur;
use App\Models\Izindinas;
use App\Models\Jabatan;
use App\Models\Jamkerja;
use App\Models\Karyawan;
use App\Models\Pengaturanumum;
use App\Models\Presensi;
use App\Models\Setjamkerjabydate;
use App\Models\Setjamkerjabyday;
use App\Models\Setjamkerjabydept;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;

class FacerecognitionpresensiController extends Controller
{
    public function index()
    {
        return view('facerecognition-presensi.index');
    }

    public function scan($nik)
    {
        // Validasi NIK
        $karyawan = Karyawan::where('nik', $nik)->first();

        if (!$karyawan) {
            return response()->json(['status' => false, 'message' => 'NIK tidak ditemukan'], 404);
        }

        if ($karyawan->status_aktif_karyawan != '1') {
            return response()->json(['status' => false, 'message' => 'Karyawan tidak aktif'], 400);
        }

        $cabang = Cabang::where('kode_cabang', $karyawan->kode_cabang)->first();

        $data = [
            'karyawan' => $karyawan,
            'cabang' => $cabang,
            'nik' => $nik
        ];

        return view('facerecognition-presensi.scan', $data);
    }

    public function scanAny()
    {
        return view('facerecognition-presensi.scan_any');
    }

    public function store(Request $request)
    {
        $generalsetting = Pengaturanumum::where('id', 1)->first();

        // Ambil NIK dari request
        $nik = $request->nik;

        $karyawan = Karyawan::where('nik', $nik)->first();

        if (!$karyawan) {
            return response()->json(['status' => false, 'message' => 'NIK tidak ditemukan'], 404);
        }

        if ($karyawan->status_aktif_karyawan != '1') {
            return response()->json(['status' => false, 'message' => 'Karyawan tidak aktif'], 400);
        }


        $status = $request->status;
        $lokasi = $request->lokasi;
        $kode_jam_kerja = $request->kode_jam_kerja;

        $tanggal_sekarang = date("Y-m-d");
        $jam_sekarang = date("H:i");
        $tanggal_kemarin = date("Y-m-d", strtotime("-1 days"));
        $tanggal_besok = date("Y-m-d", strtotime("+1 days"));

        // Cek Presensi Kemarin
        $presensi_kemarin = Presensi::where('nik', $karyawan->nik)
            ->join('presensi_jamkerja', 'presensi.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('nik', $karyawan->nik)
            ->where('tanggal', $tanggal_kemarin)->first();

        $lintas_hari = $presensi_kemarin ? $presensi_kemarin->lintashari : 0;

        // Get Lokasi User
        $koordinat_user = explode(",", $lokasi);
        $latitude_user = $koordinat_user[0];
        $longitude_user = $koordinat_user[1];

        // Get Lokasi Kantor
        $cabang = Cabang::where('kode_cabang', $karyawan->kode_cabang)->first();
        $lokasi_kantor = $request->lokasi_cabang ?? ($cabang ? $cabang->lokasi_cabang : '0,0');

        $koordinat_kantor = explode(",", $lokasi_kantor);
        $latitude_kantor = $koordinat_kantor[0];
        $longitude_kantor = $koordinat_kantor[1];

        $jarak = hitungjarak($latitude_kantor, $longitude_kantor, $latitude_user, $longitude_user);
        $radius = round($jarak["meters"]);

        $in_out = $status == 1 ? "in" : "out";
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";

        $jam_kerja = Jamkerja::where('kode_jam_kerja', $kode_jam_kerja)->first();
        $jam_presensi = $tanggal_sekarang . " " . $jam_sekarang;

        $batas_jam_absen = $generalsetting->batas_jam_absen * 60;
        $batas_jam_absen_pulang = $generalsetting->batas_jam_absen_pulang * 60;

        // Jika Kemarin Melakukan Presensi
        if ($presensi_kemarin != null) {
            // Jika Presensi Kemarin Lintas Hari
            if ($presensi_kemarin->lintashari == 1) {
                // Jika Jam Sekarang Lebih Besar dari batas_presensi_lintashari
                if ($jam_sekarang > $generalsetting->batas_presensi_lintashari) {
                    $tanggal_pulang = $tanggal_besok;
                    $jam_kerja_pulang = $jam_kerja->jam_pulang;
                    $tanggal_presensi = $tanggal_sekarang;
                } else {
                    $tanggal_pulang = $tanggal_sekarang;
                    $jam_kerja_pulang = $presensi_kemarin->jam_pulang;
                    $tanggal_presensi = $tanggal_kemarin;
                }
            } else {
                if ($jam_kerja->lintashari == 1) {
                    $tanggal_pulang = $tanggal_besok;
                    $jam_kerja_pulang = $jam_kerja->jam_pulang;
                    $tanggal_presensi = $tanggal_sekarang;
                } else {
                    $tanggal_pulang = $tanggal_sekarang;
                    $jam_kerja_pulang = $jam_kerja->jam_pulang;
                    $tanggal_presensi = $tanggal_sekarang;
                }
            }
        } else {
            if ($jam_kerja->lintashari == 1) {
                $tanggal_pulang = $tanggal_besok;
                $jam_kerja_pulang = $jam_kerja->jam_pulang;
                $tanggal_presensi = $tanggal_sekarang;
            } else {
                $tanggal_pulang = $tanggal_sekarang;
                $jam_kerja_pulang = $jam_kerja->jam_pulang;
                $tanggal_presensi = $tanggal_sekarang;
            }
        }

        $formatName = $karyawan->nik . "-" . $tanggal_presensi . "-" . $in_out;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        $jam_masuk = $tanggal_presensi . " " . date('H:i', strtotime($jam_kerja->jam_masuk));
        // Jam Mulai Absen adalah 60 Menit Sebelum Jam Masuk
        $jam_mulai_masuk = $tanggal_presensi . " " . date('H:i', strtotime('-' . $batas_jam_absen . ' minutes', strtotime($jam_masuk)));
        $jam_akhir_masuk = $tanggal_presensi . " " . date('H:i', strtotime('+' . $batas_jam_absen . ' minutes', strtotime($jam_masuk)));
        $jam_pulang = $tanggal_pulang . " " . $jam_kerja_pulang;

        $jam_mulai_pulang = date('Y-m-d H:i', strtotime('-' . $batas_jam_absen_pulang . ' minutes', strtotime($jam_pulang)));





        $presensi_hariini = Presensi::where('nik', $karyawan->nik)
            ->where('tanggal', $tanggal_presensi)
            ->first();

        // Cek Radius

        if ($status == 1) {
            if ($presensi_hariini && $presensi_hariini->jam_in != null) {
                return response()->json(['status' => false, 'message' => 'Anda Sudah Absen Masuk Hari Ini', 'notifikasi' => 'notifikasi_sudahabsen'], 400);
            } else if ($jam_presensi < $jam_mulai_masuk && $generalsetting->batasi_absen == 1) {
                return response()->json(['status' => false, 'message' => 'Maaf Belum Waktunya Absen Masuk, Waktu Absen Dimulai Pukul ' . formatIndo3($jam_mulai_masuk), 'notifikasi' => 'notifikasi_mulaiabsen'], 400);
            } else if ($jam_presensi > $jam_akhir_masuk && $generalsetting->batasi_absen == 1) {
                return response()->json(['status' => false, 'message' => 'Maaf Waktu Absen Masuk Sudah Habis ', 'notifikasi' => 'notifikasi_akhirabsen'], 400);
            } else {
                try {
                    if ($presensi_hariini != null) {
                        Presensi::where('id', $presensi_hariini->id)->update([
                            'jam_in' => $jam_presensi,
                            'lokasi_in' => $lokasi,
                            'foto_in' => $fileName
                        ]);
                    } else {
                        Presensi::create([
                            'nik' => $karyawan->nik,
                            'tanggal' => $tanggal_presensi,
                            'jam_in' => $jam_presensi,
                            'jam_out' => null,
                            'lokasi_in' => $lokasi,
                            'lokasi_out' => null,
                            'foto_in' => $fileName,
                            'foto_out' => null,
                            'kode_jam_kerja' => $kode_jam_kerja,
                            'status' => 'h'
                        ]);
                    }
                    Storage::put($file, $image_base64);

                    // Kirim Notifikasi Ke WA
                    if ($karyawan->no_hp != null || $karyawan->no_hp != "" && $generalsetting->notifikasi_wa == 1) {
                        $message = "Terimakasih, Hari ini " . $karyawan->nama_karyawan . " absen masuk pada " . $jam_presensi . " Semagat Bekerja";
                        $this->sendwa($karyawan->no_hp, $message);
                    }
                    return response()->json(['status' => true, 'message' => 'Berhasil Absen Masuk', 'notifikasi' => 'notifikasi_absenmasuk'], 200);
                } catch (\Exception $e) {
                    return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
                }
            }
        } else {
            if ($presensi_hariini && $presensi_hariini->jam_out != null) {
                return response()->json(['status' => false, 'message' => 'Anda Sudah Absen Pulang Hari Ini', 'notifikasi' => 'notifikasi_sudahabsen'], 400);
            } else if ($jam_presensi < $jam_mulai_pulang && $generalsetting->batasi_absen == 1) {
                return response()->json(['status' => false, 'message' => 'Maaf Belum Waktunya Absen Pulang, Waktu Absen Dimulai Pukul ' . formatIndo3($jam_mulai_pulang), 'notifikasi' => 'notifikasi_mulaiabsen'], 400);
            } else {
                try {
                    if ($presensi_hariini != null) {
                        Presensi::where('id', $presensi_hariini->id)->update([
                            'jam_out' => $jam_presensi,
                            'lokasi_out' => $lokasi,
                            'foto_out' => $fileName
                        ]);
                    } else {
                        Presensi::create([
                            'nik' => $karyawan->nik,
                            'tanggal' => $tanggal_presensi,
                            'jam_in' => null,
                            'jam_out' => $jam_presensi,
                            'lokasi_in' => null,
                            'lokasi_out' => $lokasi,
                            'foto_in' => null,
                            'foto_out' => $fileName,
                            'kode_jam_kerja' => $kode_jam_kerja,
                            'status' => 'h'
                        ]);
                    }
                    Storage::put($file, $image_base64);

                    // Kirim Notifikasi Ke WA
                    if ($karyawan->no_hp != null || $karyawan->no_hp != "" && $generalsetting->notifikasi_wa == 1) {
                        $message = "Terimakasih, Hari ini " . $karyawan->nama_karyawan . " absen Pulang pada " . $jam_presensi . "Hati Hati di Jalan";
                        $this->sendwa($karyawan->no_hp, $message);
                    }
                    return response()->json(['status' => true, 'message' => 'Berhasil Absen Pulang', 'notifikasi' => 'notifikasi_absenpulang'], 200);
                } catch (\Exception $e) {
                    return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
                }
            }
        }
    }

    function sendwa($no_hp, $message)
    {
        $generalsetting = Pengaturanumum::where('id', 1)->first();
        $apiKey = $generalsetting->wa_api_key;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $no_hp,
                'message' => $message,
                'filename' => 'filename',
                'schedule' => 0,
                'typing' => true,
                'delay' => '2',
                'countryCode' => '62',
                'followup' => 0,
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $apiKey
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            echo $error_msg;
        }
    }

    public function getKaryawan($nik)
    {
        try {
            // Ambil data karyawan dengan join ke tabel jabatan
            $karyawan = Karyawan::leftJoin('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                ->select('karyawan.*', 'jabatan.nama_jabatan')
                ->where('karyawan.nik', $nik)
                ->first();

            if (!$karyawan) {
                return response()->json(['status' => false, 'message' => 'NIK tidak ditemukan'], 404);
            }

            if ($karyawan->status_aktif_karyawan != '1') {
                return response()->json(['status' => false, 'message' => 'Karyawan tidak aktif'], 400);
            }

            // Ambil jam kerja karyawan untuk hari ini
            $jamKerja = $this->getJamKerjaKaryawan($karyawan);

            return response()->json([
                'status' => true,
                'karyawan' => $karyawan,
                'jam_kerja' => $jamKerja
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Fungsi untuk mendapatkan jam kerja karyawan (diambil dari PresensiController)
    private function getJamKerjaKaryawan($karyawan)
    {

        $hariini = date("Y-m-d");
        $namahari = $this->getnamaHari(date('D', strtotime($hariini)));
        $kode_dept = $karyawan->kode_dept;

        // Cek Jam Kerja By Date
        $jamkerja = Setjamkerjabydate::join('presensi_jamkerja', 'presensi_jamkerja_bydate.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('nik', $karyawan->nik)
            ->where('tanggal', $hariini)
            ->first();

        // Jika Tidak Memiliki Jam Kerja By Date
        if ($jamkerja == null) {
            // Cek Jam Kerja harian / Jam Kerja Khusus / Jam Kerja Per Orangannya
            $jamkerja = Setjamkerjabyday::join('presensi_jamkerja', 'presensi_jamkerja_byday.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->where('nik', $karyawan->nik)
                ->where('hari', $namahari)
                ->first();

            // Jika Jam Kerja Harian Kosong
            if ($jamkerja == null) {
                $jamkerja = Detailsetjamkerjabydept::join('presensi_jamkerja_bydept', 'presensi_jamkerja_bydept_detail.kode_jk_dept', '=', 'presensi_jamkerja_bydept.kode_jk_dept')
                    ->join('presensi_jamkerja', 'presensi_jamkerja_bydept_detail.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                    ->where('kode_dept', $kode_dept)
                    ->where('kode_cabang', $karyawan->kode_cabang)
                    ->where('hari', $namahari)
                    ->first();
            }
        }

        return $jamkerja;
    }

    // Fungsi helper untuk mendapatkan nama hari
    private function getnamaHari($hari)
    {
        $namaHari = [
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu'
        ];

        return $namaHari[$hari] ?? $hari;
    }

    public function getAllWajah()
    {
        try {
            // Debug: Log query
            Log::info('Getting all employee face data');

            // Ambil semua data wajah dari tabel facerecognition
            $allWajah = Facerecognition::all();
            Log::info('Found ' . $allWajah->count() . ' face records');

            // Group by NIK
            $wajahByNik = $allWajah->groupBy('nik');
            Log::info('Grouped by NIK:', $wajahByNik->keys()->toArray());

            // Ambil data karyawan untuk NIK yang memiliki wajah dengan join ke tabel jabatan
            $karyawanWithWajah = Karyawan::leftJoin('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                ->select('karyawan.*', 'jabatan.nama_jabatan')
                ->where('karyawan.status_aktif_karyawan', '1')
                ->whereIn('karyawan.nik', $wajahByNik->keys())
                ->get();

            Log::info('Found ' . $karyawanWithWajah->count() . ' active employees with face data');

            $result = [];
            foreach ($karyawanWithWajah as $karyawan) {
                $wajahData = [];
                $wajahRecords = $wajahByNik->get($karyawan->nik, collect());

                foreach ($wajahRecords as $wajah) {
                    $wajahData[] = [
                        'wajah' => $wajah->wajah,
                        'created_at' => $wajah->created_at
                    ];
                }

                $result[] = [
                    'nik' => $karyawan->nik,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'kode_jabatan' => $karyawan->kode_jabatan,
                    'nama_jabatan' => $karyawan->nama_jabatan,
                    'status_aktif_karyawan' => $karyawan->status_aktif_karyawan,
                    'wajah_data' => $wajahData
                ];
            }

            // Jika tidak ada data wajah sama sekali, ambil semua karyawan aktif untuk testing
            if (count($result) == 0) {
                Log::info('No face data found, getting all active employees for testing');
                $allKaryawan = Karyawan::leftJoin('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                    ->select('karyawan.*', 'jabatan.nama_jabatan')
                    ->where('karyawan.status_aktif_karyawan', '1')
                    ->get();

                foreach ($allKaryawan as $karyawan) {
                    $result[] = [
                        'nik' => $karyawan->nik,
                        'nama_karyawan' => $karyawan->nama_karyawan,
                        'kode_jabatan' => $karyawan->kode_jabatan,
                        'nama_jabatan' => $karyawan->nama_jabatan,
                        'status_aktif_karyawan' => $karyawan->status_aktif_karyawan,
                        'wajah_data' => []
                    ];
                }
            }

            Log::info('Returning ' . count($result) . ' employee records');
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error in getAllWajah: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

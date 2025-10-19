<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Facerecognition;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Pengaturanumum;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        $user = User::where('id', '=', auth()->user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $qlembur = Lembur::query();
        $qlembur->join('karyawan', 'lembur.nik', '=', 'karyawan.nik');
        $qlembur->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan');
        $qlembur->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $qlembur->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $qlembur->whereBetween('lembur.tanggal', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nama_karyawan)) {
            $qlembur->where('karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        if (!empty($request->kode_cabang)) {
            $qlembur->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $qlembur->where('karyawan.kode_dept', $request->kode_dept);
        }

        if (!empty($request->status) || $request->status === '0') {
            $qlembur->where('lembur.status', $request->status);
        }

        if ($user->hasRole('karyawan')) {
            $qlembur->where('lembur.nik', $userkaryawan->nik);
        }

        $qlembur->orderBy('lembur.status');
        $qlembur->orderBy('lembur.tanggal', 'desc');
        $lembur = $qlembur->paginate(15);
        $lembur->appends($request->all());
        $data['lembur'] = $lembur;
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();

        if ($user->hasRole('karyawan')) {
            return view('lembur.index-karyawan', $data);
        } else {
            return view('lembur.index', $data);
        }
    }


    public function create()
    {
        $user = User::where('id', '=', auth()->user()->id)->first();
        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();
        $data['karyawan'] = $karyawan;

        // $user = User::where('id', '=', auth()->user()->id)->first();
        // if ($user->hasRole('karyawan')) {
        //     return view('izincuti.create-mobile', $data);
        // }

        if ($user->hasRole('karyawan')) {
            return view('lembur.create-karyawan', $data);
        } else {
            return view('lembur.create', $data);
        }
    }


    public function store(Request $request)
    {
        $user = User::where('id', '=', auth()->user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        if (!$user->hasRole('karyawan')) {
            $nik = $request->nik;
            $request->validate([
                'nik' => 'required',
                'dari' => 'required',
                'sampai' => 'required',
                'keterangan' => 'required',
            ]);
        } else {
            $nik = $userkaryawan->nik;
            $request->validate([
                'dari' => 'required',
                'sampai' => 'required',
                'keterangan' => 'required',
            ]);
        }


        try {
            Lembur::create([
                'nik' => $nik,
                'tanggal' => date('Y-m-d', strtotime($request->dari)),
                'lembur_mulai' => $request->dari,
                'lembur_selesai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'status' => 0,
            ]);
            if ($user->hasRole('karyawan')) {
                return Redirect::route('lembur.index')->with('success', 'Data Lembur berhasil disimpan');
            } else {
                return Redirect::back()->with('success', 'Data Lembur berhasil disimpan');
            }
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Data Lembur gagal disimpan' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $lembur = Lembur::where('id', $id)->first();
        $qkaryawan = Karyawan::query();
        $qkaryawan->select('karyawan.nik', 'karyawan.nama_karyawan');
        $karyawan = $qkaryawan->get();
        $data['karyawan'] = $karyawan;
        $data['lembur'] = $lembur;
        return view('lembur.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'nik' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
        ]);

        try {
            Lembur::where('id', $id)->update([
                'nik' => $request->nik,
                'tanggal' => date('Y-m-d', strtotime($request->dari)),
                'lembur_mulai' => $request->dari,
                'lembur_selesai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'lembur_in' => $request->lembur_in,
                'lembur_out' => $request->lembur_out,
                'status' => $request->lembur_in && $request->lembur_out ? 1 : 0,
            ]);
            return Redirect::back()->with('success', 'Data Lembur berhasil disimpan');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Data Lembur gagal disimpan' . $e->getMessage());
        }
    }


    public function approve($id)
    {
        $id = Crypt::decrypt($id);
        $lembur = Lembur::where('id', $id)
            ->join('karyawan', 'lembur.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['lembur'] = $lembur;
        return view('lembur.approve', $data);
    }


    public function storeapprove(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        try {
            if (isset($request->approve)) {
                Lembur::where('id', $id)->update([
                    'status' => 1,
                ]);
                return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
            } else {
                Lembur::where('id', $id)->update([
                    'status' => 2,
                ]);
                return Redirect::back()->with(messageSuccess('Data Ajuan Lembur Ditolak'));
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cancelapprove($id)
    {
        $id = Crypt::decrypt($id);
        try {
            Lembur::where('id', $id)->update([
                'status' => 0
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $lembur = Lembur::where('id', $id)
            ->join('karyawan', 'lembur.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $data['lembur'] = $lembur;
        return view('lembur.show', $data);
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            Lembur::where('id', $id)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function createpresensi($id)
    {
        $id = Crypt::decrypt($id);
        $lembur = Lembur::where('id', $id)
            ->select(
                'lembur.*',
                'karyawan.nama_karyawan',
                'cabang.nama_cabang',
                'cabang.radius_cabang',
                'cabang.lokasi_cabang'
            )
            ->join('karyawan', 'lembur.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $data['wajah'] = Facerecognition::where('nik', $lembur->nik)->count();
        $data['lembur'] = $lembur;
        return view('lembur.create-presensi', $data);
    }

    public function storepresensi(Request $request)
    {
        $id_lembur = $request->id_lembur;
        $lembur = Lembur::where('id', $id_lembur)
            ->select(
                'lembur.*',
                'karyawan.nama_karyawan',
                'karyawan.lock_location',
                'cabang.nama_cabang',
                'cabang.radius_cabang',
                'cabang.lokasi_cabang'
            )
            ->join('karyawan', 'lembur.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        $status_lock_location = $lembur->lock_location;
        $status = $request->status;
        $lokasi = $request->lokasi;

        $tanggal_sekarang = date("Y-m-d");
        $jam_sekarang = date("H:i");

        //Get Lokasi User
        $koordinat_user = explode(",", $lokasi);
        $latitude_user = $koordinat_user[0];
        $longitude_user = $koordinat_user[1];

        //Get Lokasi Kantor
        $lokasi_kantor = $lembur->lokasi_cabang;

        $koordinat_kantor = explode(",", $lokasi_kantor);
        $latitude_kantor = $koordinat_kantor[0];
        $longitude_kantor = $koordinat_kantor[1];

        $jarak = hitungjarak($latitude_kantor, $longitude_kantor, $latitude_user, $longitude_user);
        $radius = round($jarak["meters"]);


        $in_out = $status == 1 ? "in" : "out";
        $image = $request->image;
        $folderPath = "public/uploads/lembur/";
        $formatName = $lembur->nik . "-" . $tanggal_sekarang . "-" . $in_out;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        $tanggal_presensi = $tanggal_sekarang;
        $jam_presensi = $tanggal_presensi . " " . $jam_sekarang;
        $batas_jam_absen = 30;


        $mulai_lembur = $lembur->lembur_mulai;
        //Jam Mulai Absen adalah 60 Menit Sebelum Jam Masuk
        $jam_mulai_masuk = date('Y-m-d H:i', strtotime('-' . $batas_jam_absen . ' minutes', strtotime($mulai_lembur)));

        //Jamulai Absen Pulang adalah 1 Jam dari Jam Masuk
        $jam_mulai_pulang =  date('Y-m-d H:i', strtotime('+' . $batas_jam_absen . ' minutes', strtotime($mulai_lembur)));


        //return $jam_mulai_pulang;
        $jam_pulang = $lembur->lembur_selesai;

        if ($status_lock_location == 1 && $radius > $lembur->radius_cabang) {
            return response()->json(['status' => false, 'message' => 'Anda Berada Di Luar Radius Kantor, Jarak Anda ' . formatAngka($radius) . ' Meters Dari Kantor', 'notifikasi' => 'notifikasi_radius'], 400);
        } else {
            if ($status == 1) {
                if ($lembur->lembur_in != null) {
                    return response()->json(['status' => false, 'message' => 'Anda Sudah Memulai Absen Lembur', 'notifikasi' => 'notifikasi_sudahabsen'], 400);
                } else if ($jam_presensi < $jam_mulai_masuk) {
                    return response()->json(['status' => false, 'message' => 'Maaf Belum Waktunya Absen Masuk, Waktu Absen Dimulai Pukul ' . formatIndo3($jam_mulai_masuk), 'notifikasi' => 'notifikasi_mulaiabsen'], 400);
                } else if ($jam_presensi > $jam_mulai_pulang) {
                    return response()->json(['status' => false, 'message' => 'Maaf Waktu Absen Masuk Sudah Habis ', 'notifikasi' => 'notifikasi_akhirabsen'], 400);
                } else {
                    try {
                        Lembur::where('id', $id_lembur)->update([
                            'lembur_in' => $jam_presensi,
                            'lokasi_lembur_in' => $lokasi,
                            'foto_lembur_in' => $fileName
                        ]);
                        Storage::put($file, $image_base64);
                        return response()->json(['status' => true, 'message' => 'Berhasil Memulai Lembur', 'notifikasi' => 'notifikasi_absenmasuk'], 200);
                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
                    }
                }
            } else {
                if ($lembur->lembur_out != null) {
                    return response()->json(['status' => false, 'message' => 'Anda Sudah Absen Pulang', 'notifikasi' => 'notifikasi_sudahabsen'], 400);
                } else if ($jam_presensi < $jam_mulai_pulang) {
                    return response()->json(['status' => false, 'message' => 'Maaf Belum Waktunya Absen Pulang, Waktu Absen Dimulai Pukul ' . formatIndo3($jam_mulai_pulang), 'notifikasi' => 'notifikasi_mulaiabsen'], 400);
                } else if ($jam_presensi > $jam_pulang) {
                    return response()->json(['status' => false, 'message' => 'Maaf Waktu Absen Masuk Sudah Habis ', 'notifikasi' => 'notifikasi_akhirabsen'], 400);
                } else {
                    try {
                        Lembur::where('id', $id_lembur)->update([
                            'lembur_out' => $jam_presensi,
                            'lokasi_lembur_out' => $lokasi,
                            'foto_lembur_out' => $fileName
                        ]);
                        Storage::put($file, $image_base64);
                        return response()->json(['status' => true, 'message' => 'Berhasil Absen Pulang', 'notifikasi' => 'notifikasi_absenpulang'], 200);
                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
                    }
                }
            }
        }
    }
}

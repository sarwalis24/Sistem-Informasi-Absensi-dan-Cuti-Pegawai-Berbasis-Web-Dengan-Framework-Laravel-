<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Facerecognition;
use App\Models\Jabatan;
use App\Models\Jamkerja;
use App\Models\Karyawan;
use App\Models\Pengaturanumum;
use App\Models\Setjamkerjabydate;
use App\Models\Setjamkerjabyday;
use App\Models\Statuskawin;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Imports\KaryawanImport;
use App\Exports\TemplateKaryawanExport;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*', 'departemen.nama_dept', 'jabatan.nama_jabatan', 'cabang.nama_cabang', 'id_user');
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $query->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan');
        $query->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('users_karyawan', 'karyawan.nik', '=', 'users_karyawan.nik');
        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        if (!empty($request->kode_group)) {
            $query->where('karyawan.kode_group', $request->kode_group);
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
         $query->orderBy('nama_karyawan');
        $karyawan = $query->paginate(15);
        $karyawan->appends(request()->all());

        $data['karyawan'] = $karyawan;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('datamaster.karyawan.index', $data);
    }


    public function create()
    {

        $data['status_kawin'] = Statuskawin::orderBy('kode_status_kawin')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['jabatan'] = Jabatan::orderBy('kode_jabatan')->get();
        return view('datamaster.karyawan.create', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'no_ktp' => 'required',
            'nama_karyawan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'kode_status_kawin' => 'required',
            'pendidikan_terakhir' => 'required',
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
            'kode_jabatan' => 'required',
            'tanggal_masuk' => 'required',
            'status_karyawan' => 'required'
        ]);

        try {
            $data_foto = [];
            if ($request->hasfile('foto')) {
                $foto_name =  $request->nik . "." . $request->file('foto')->getClientOriginalExtension();
                $destination_foto_path = "/public/karyawan";
                $foto = $foto_name;
                $data_foto = [
                    'foto' => $foto
                ];
            }
            $data_karyawan = [
                'nik' => $request->nik,
                'no_ktp' => $request->no_ktp,
                'nama_karyawan' => $request->nama_karyawan,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_jabatan' => $request->kode_jabatan,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_karyawan' => $request->status_karyawan,
                'lock_location' => 1,
                'status_aktif_karyawan' => 1,
                'password' => Hash::make('12345')
            ];
            $data = array_merge($data_karyawan, $data_foto);
            $simpan = Karyawan::create($data);
            if ($simpan) {
                if ($request->hasfile('foto')) {
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name);
                }
            }
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($nik)
    {
        $nik = Crypt::decrypt($nik);
        $data['karyawan'] = Karyawan::where('nik', $nik)->first();
        $data['status_kawin'] = Statuskawin::orderBy('kode_status_kawin')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['jabatan'] = Jabatan::orderBy('kode_jabatan')->get();
        return view('datamaster.karyawan.edit', $data);
    }


    public function update($nik, Request $request)
    {
        $nik = Crypt::decrypt($nik);
        $request->validate([
            'nik' => 'required',
            'no_ktp' => 'required',
            'nama_karyawan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'kode_status_kawin' => 'required',
            'pendidikan_terakhir' => 'required',
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
            'kode_jabatan' => 'required',
            'tanggal_masuk' => 'required',
            'status_karyawan' => 'required'
        ]);

        try {
            $karyawan = Karyawan::where('nik', $nik)->first();
            $data_foto = [];
            if ($request->hasfile('foto')) {
                $foto_name =  $request->nik . "." . $request->file('foto')->getClientOriginalExtension();
                $destination_foto_path = "/public/karyawan";
                $foto = $foto_name;
                $data_foto = [
                    'foto' => $foto
                ];
            }

            $data_karyawan = [
                'nik' => $request->nik,
                'no_ktp' => $request->no_ktp,
                'nama_karyawan' => $request->nama_karyawan,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_jabatan' => $request->kode_jabatan,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_karyawan' => $request->status_karyawan,
                'status_aktif_karyawan' => $request->status_aktif_karyawan,
                'pin' => $request->pin
            ];

            $data = array_merge($data_karyawan, $data_foto);
            $simpan = Karyawan::where('nik', $nik)->update($data);
            if ($simpan) {
                if ($request->hasfile('foto')) {
                    Storage::delete($destination_foto_path . "/" . $karyawan->foto);
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name);
                }
            }
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function lockunlocklocation($nik)
    {
        $nik = Crypt::decrypt($nik);
        try {
            $karyawan = Karyawan::where('nik', $nik)->first();
            if ($karyawan->lock_location == '1') {
                $lock_location = 0;
            } else {
                $lock_location = 1;
            }

            Karyawan::where('nik', $nik)->update([
                'lock_location' => $lock_location
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function lockunlockjamkerja($nik)
    {
        $nik = Crypt::decrypt($nik);
        try {
            $karyawan = Karyawan::where('nik', $nik)->first();
            if ($karyawan->lock_jam_kerja == '1') {
                $lock_jam_kerja = 0;
            } else {
                $lock_jam_kerja = 1;
            }

            Karyawan::where('nik', $nik)->update([
                'lock_jam_kerja' => $lock_jam_kerja
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($nik)
    {
        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('status_kawin', 'karyawan.kode_status_kawin', '=', 'status_kawin.kode_status_kawin')

            ->first();
        $user_karyawan = Userkaryawan::where('nik', $nik)->first();
        $user = $user_karyawan ? User::where('id', $user_karyawan->id_user)->first() : null;
        $karyawan_wajah = Facerecognition::where('nik', $nik)->get();
        $data['karyawan'] = $karyawan;
        $data['user'] = $user;
        $data['karyawan_wajah'] = $karyawan_wajah;
        return view('datamaster.karyawan.show', $data);
    }


    public function destroy($nik)
    {
        $nik = Crypt::decrypt($nik);
        try {
            $karyawan = Karyawan::where('nik', $nik)->first();
            $user_karyawan = Userkaryawan::where('nik', $nik)->first();
            if (!empty($user_karyawan)) {
                User::where('id', $user_karyawan->id_user)->delete();
                Userkaryawan::where('nik', $nik)->delete();
            }
            //$facerecognition = Facerecognition::where('nik', $nik)->get();
            // foreach ($facerecognition as $fr) {
            //     $nama_file = $facerecognition->wajah;
            //     $nama_folder = $karyawan->nik . "-" . getNamaDepan(strtolower($karyawan->nama_karyawan));
            //     $path = 'public/uploads/facerecognition/' . $nama_folder . "/" . $nama_file;
            //     Storage::delete($path);
            // }

            $nama_folder = $karyawan->nik . "-" . getNamaDepan(strtolower($karyawan->nama_karyawan));
            $path_folder = 'public/uploads/facerecognition/' . $nama_folder;
            Storage::deleteDirectory($path_folder);


            $nama_file_foto = $karyawan->foto;
            $path_foto = '/public/karyawan/' . $nama_file_foto;
            Storage::delete($path_foto);
            Karyawan::where('nik', $nik)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function setjamkerja($nik)
    {
        $nik = Crypt::decrypt($nik);
        $data['karyawan'] = Karyawan::where('nik', $nik)
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['jamkerja'] = Jamkerja::orderBy('kode_jam_kerja')->get();
        $data['jamkerjabyday'] = Setjamkerjabyday::where('nik', $nik)->pluck('kode_jam_kerja', 'hari')->toArray();
        // dd($data['jamkerjabyday']);
        return view('datamaster.karyawan.setjamkerja', $data);
    }


    public function storejamkerjabyday(Request $request, $nik)
    {
        $nik = Crypt::decrypt($nik);
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        DB::beginTransaction();
        try {
            Setjamkerjabyday::where('nik', $nik)->delete();
            for ($i = 0; $i < count($hari); $i++) {
                if (!empty($kode_jam_kerja[$i])) {
                    Setjamkerjabyday::create([
                        'nik' => $nik,
                        'hari' => $hari[$i],
                        'kode_jam_kerja' => $kode_jam_kerja[$i]
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function storejamkerjabydate(Request $request)
    {
        $cek = Setjamkerjabydate::where('nik', $request->nik)->where('tanggal', $request->tanggal)->first();
        if (!empty($cek)) {
            return response()->json(['success' => false, 'message' => 'Karyawan Sudah Memiliki Jadwal pada Tanggal Ini']);
        }
        try {
            Setjamkerjabydate::create([
                'nik' => $request->nik,
                'tanggal' => $request->tanggal,
                'kode_jam_kerja' => $request->kode_jam_kerja
            ]);

            return response()->json(['success' => true, 'message' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getjamkerjabydate(Request $request)
    {
        $nik = $request->nik;
        $tanggal = $request->tanggal;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $jamkerjabydate = Setjamkerjabydate::where('nik', $nik)
            ->join('presensi_jamkerja', 'presensi_jamkerja.kode_jam_kerja', '=', 'presensi_jamkerja_bydate.kode_jam_kerja')
            ->whereRaw('MONTH(tanggal) = ' . $bulan . ' AND YEAR(tanggal) = ' . $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();


        return response()->json($jamkerjabydate);
    }

    public function deletejamkerjabydate(Request $request)

    {
        // dd($request);
        try {
            Setjamkerjabydate::where('nik', $request->nik)->where('tanggal', $request->tanggal)->delete();
            return response()->json(['success' => true, 'message' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function createuser($nik)
    {
        $generalsetting = Pengaturanumum::first();
        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)->first();
        DB::beginTransaction();
        try {
            //code...
            $user = User::create([
                'name' => $karyawan->nama_karyawan,
                'username' => $karyawan->nik,
                'password' => Hash::make($karyawan->nik),
                'email' => strtolower(removeTitik($karyawan->nik)) . '@' . $generalsetting->domain_email,
            ]);

            Userkaryawan::create([
                'nik' => $nik,
                'id_user' => $user->id
            ]);

            $user->assignRole('karyawan');
            DB::commit();
            return Redirect::route('karyawan.index')->with(messageSuccess('User Berhasil Dibuat'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deleteuser($nik)
    {
        $nik = Crypt::decrypt($nik);
        try {
            $user_karyawan = Userkaryawan::where('nik', $nik)->first();
            User::where('id', $user_karyawan->id_user)->delete();
            Userkaryawan::where('nik', $nik)->delete();
            return Redirect::back()->with(messageSuccess('User Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Data User gagal dihapus ' . $e->getMessage()));
        }
    }

    public function import()
    {
        return view('datamaster.karyawan.import_modal');
    }

    public function download_template()
    {
        return Excel::download(new TemplateKaryawanExport, 'template_import_karyawan.xlsx');
    }

    public function import_proses(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $file = $request->file('file');
            Excel::import(new KaryawanImport, $file);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function getkaryawan(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $karyawan = Karyawan::where('kode_cabang', $kode_cabang)->get();
        return response()->json($karyawan);
    }

    public function idcard($nik)
    {
        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->first();
        $data['karyawan'] = $karyawan;
        $generalsetting = Pengaturanumum::where('id', 1)->first();
        $data['generalsetting'] = $generalsetting;
        return view('datamaster.karyawan.idcard', $data);
    }
}

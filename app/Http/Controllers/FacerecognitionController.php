<?php

namespace App\Http\Controllers;

use App\Models\Facerecognition;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class FacerecognitionController extends Controller
{
    public function create($nik)
    {
        $data['nik'] = Crypt::decrypt($nik);
        return view('facerecognition.create', $data);
    }

    public function store(Request $request)
    {
        $karyawan = Karyawan::where('nik', $request->nik)->first();
        $nama_folder = $karyawan->nik . "-" . getNamaDepan($karyawan->nama_karyawan);
        $folderPath = "public/uploads/facerecognition/" . $request->nik . "-" . getNamaDepan(strtolower($karyawan->nama_karyawan)) . "/";

        // dd(storage_path($folderPath));
        // Membuat folder jika belum ada dan set permission
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath, 0775, true);
            Storage::setVisibility($folderPath, 'public');
            // chmod(storage_path($folderPath), 0775);
        }

        try {
            $saved = [];
            // Jika multi-capture (images array)
            if ($request->has('images')) {
                $images = json_decode($request->images, true);
                $cekWajah = Facerecognition::where('nik', $request->nik)->count();
                $urutan = $cekWajah + 1;
                foreach ($images as $img) {
                    $direction = isset($img['direction']) ? $img['direction'] : 'front';
                    $image = $img['image'];
                    $image_parts = explode(';base64', $image);
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = $urutan . "_" . $direction . ".png";
                    $file = $folderPath . $fileName;
                    Facerecognition::create([
                        'nik' => $request->nik,
                        'wajah' => $fileName
                    ]);
                    Storage::put($file, $image_base64);
                    $saved[] = $fileName;
                    $urutan++;
                }
                return response()->json(['success' => true, 'message' => count($saved) . ' gambar berhasil disimpan', 'files' => $saved]);
            } else if ($request->has('image')) {
                // Backward compatibility: satu gambar saja
                $cekWajah = Facerecognition::where('nik', $request->nik)->count();
                $formatName = $cekWajah + 1;
                $image = $request->image;
                $image_parts = explode(';base64', $image);
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = $formatName . ".png";
                $file = $folderPath . $fileName;
                Facerecognition::create([
                    'nik' => $request->nik,
                    'wajah' => $fileName
                ]);
                Storage::put($file, $image_base64);
                return response()->json(['success' => true, 'message' => 'Data Berhasil Disimpan', 'file' => $fileName]);
            } else {
                return response()->json(['success' => false, 'message' => 'Tidak ada gambar yang dikirim']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $facerecognition = Facerecognition::where('id', $id)->firstorfail();
        $karyawan = Karyawan::where('nik', $facerecognition->nik)->first();
        try {
            $nama_file = $facerecognition->wajah;
            $nama_folder = $karyawan->nik . "-" . getNamaDepan(strtolower($karyawan->nama_karyawan));
            $path = 'public/uploads/facerecognition/' . $nama_folder . "/" . $nama_file;
            Storage::delete($path);
            $facerecognition->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getWajah()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $userkaryawan = Userkaryawan::where('id_user', $user->id)->first();
        $wajah = Facerecognition::where('nik', $userkaryawan->nik)->get();
        return response()->json($wajah);
    }

    // Hapus semua wajah berdasarkan NIK
    public function destroyAll($nik)
    {
        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)->first();
        if (!$karyawan) {
            return Redirect::back()->with(messageError('Karyawan tidak ditemukan'));
        }
        $folder = $karyawan->nik . '-' . getNamaDepan(strtolower($karyawan->nama_karyawan));
        $folderPath = 'public/uploads/facerecognition/' . $folder;
        try {
            // Hapus semua file di folder
            if (Storage::exists($folderPath)) {
                Storage::deleteDirectory($folderPath);
            }
            // Hapus semua record di database
            Facerecognition::where('nik', $nik)->delete();
            return Redirect::back()->with(messageSuccess('Semua data wajah berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Gagal menghapus semua wajah: ' . $e->getMessage()));
        }
    }
}

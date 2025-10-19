<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class KaryawanImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Karyawan([
            'nik' => $row['nik'],
            'no_ktp' => $row['no_ktp'],
            'nama_karyawan' => $row['nama_karyawan'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'alamat' => $row['alamat'],
            'no_hp' => $row['no_hp'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'kode_status_kawin' => $row['kode_status_kawin'],
            'pendidikan_terakhir' => $row['pendidikan_terakhir'],
            'kode_cabang' => $row['kode_cabang'],
            'kode_dept' => $row['kode_dept'],
            'kode_jabatan' => $row['kode_jabatan'],
            'tanggal_masuk' => $row['tanggal_masuk'],
            'status_karyawan' => $row['status_karyawan'],
            'kode_jadwal' => null,
            'pin' => null,
            'tanggal_nonaktif' => null,
            'tanggal_off_gaji' => null,
            'lock_location' => 1,
            'lock_jam_kerja' => 1,
            'status_aktif_karyawan' => $row['status_aktif_karyawan'] ?? 1,
            'password' => bcrypt('12345')
        ]);
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'unique:karyawan,nik'],
            'no_ktp' => 'required',
            'nama_karyawan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'alamat' => 'required',
            'no_hp' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'kode_status_kawin' => 'required|exists:status_kawin,kode_status_kawin',
            'pendidikan_terakhir' => 'required',
            'kode_cabang' => 'required|exists:cabang,kode_cabang',
            'kode_dept' => 'required|exists:departemen,kode_dept',
            'kode_jabatan' => 'required|exists:jabatan,kode_jabatan',
            'tanggal_masuk' => 'required|date_format:Y-m-d',
            'status_karyawan' => 'required',
            'status_aktif_karyawan' => 'nullable|in:0,1'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'no_ktp.required' => 'No KTP harus diisi',
            'nama_karyawan.required' => 'Nama karyawan harus diisi',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'tanggal_lahir.date_format' => 'Format tanggal lahir tidak valid (YYYY-MM-DD)',
            'alamat.required' => 'Alamat harus diisi',
            'no_hp.required' => 'No HP harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'kode_status_kawin.required' => 'Kode status kawin harus diisi',
            'kode_status_kawin.exists' => 'Kode status kawin tidak valid',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir harus diisi',
            'kode_cabang.required' => 'Kode cabang harus diisi',
            'kode_cabang.exists' => 'Kode cabang tidak valid',
            'kode_dept.required' => 'Kode departemen harus diisi',
            'kode_dept.exists' => 'Kode departemen tidak valid',
            'kode_jabatan.required' => 'Kode jabatan harus diisi',
            'kode_jabatan.exists' => 'Kode jabatan tidak valid',
            'tanggal_masuk.required' => 'Tanggal masuk harus diisi',
            'tanggal_masuk.date_format' => 'Format tanggal masuk tidak valid (YYYY-MM-DD)',
            'status_karyawan.required' => 'Status karyawan harus diisi',
            'status_aktif_karyawan.in' => 'Status aktif harus 0 atau 1'
        ];
    }
}

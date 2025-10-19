<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TemplateKaryawanExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        return [
            'nik',
            'no_ktp',
            'nama_karyawan',
            'tempat_lahir',
            'tanggal_lahir',
            'alamat',
            'no_hp',
            'jenis_kelamin',
            'kode_status_kawin',
            'pendidikan_terakhir',
            'kode_cabang',
            'kode_dept',
            'kode_jabatan',
            'tanggal_masuk',
            'status_karyawan',
            'kode_jadwal',
            'pin',
            'tanggal_nonaktif',
            'tanggal_off_gaji',
            'status_aktif_karyawan'
        ];
    }

    public function title(): string
    {
        return 'Template Import Karyawan';
    }
}

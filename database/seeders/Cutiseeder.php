<?php

namespace Database\Seeders;

use App\Models\Cuti;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Cutiseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cuti::create([
            'kode_cuti' => 'C01',
            'jenis_cuti' => 'Tahunan',
            'jumlah_hari' => 12
        ]);

        Cuti::create([
            'kode_cuti' => 'C02',
            'jenis_cuti' => 'Melahirkan',
            'jumlah_hari' => 90
        ]);

        Cuti::create([
            'kode_cuti' => 'C03',
            'jenis_cuti' => 'Khusus',
            'jumlah_hari' => 1
        ]);
    }
}

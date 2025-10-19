<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Cabangseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cabang::create([
            'kode_cabang' => 'TSM',
            'nama_cabang' => 'TASIKMALAYA',
            'alamat_cabang' => 'Jln. Perintis Kemerdekaan No. 80 Kawalu Tasikmalaya',
            'telepon_cabang' => '0265311766',
            'lokasi_cabang' => '-7.317623346580317,108.19935815408388',
            'radius_cabang' => '30',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Statuskawin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Statuskawinseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Statuskawin::create([
            'kode_status_kawin' => 'HB',
            'status_kawin' => 'Telah Berpisah Secara Hukum / Janda / Duda'
        ]);

        Statuskawin::create([
            'kode_status_kawin' => 'K0',
            'status_kawin' => 'Kawin Belum Punya Tanggungan'
        ]);

        Statuskawin::create([
            'kode_status_kawin' => 'K1',
            'status_kawin' => 'Kawin Punya Tanggungan 1'
        ]);

        Statuskawin::create([
            'kode_status_kawin' => 'K2',
            'status_kawin' => 'Kawin PUnya Tanggungan 2'
        ]);

        Statuskawin::create([
            'kode_status_kawin' => 'K3',
            'status_kawin' => 'Kawin PUnya Tanggungan 3'
        ]);

        Statuskawin::create([
            'kode_status_kawin' => 'TK',
            'status_kawin' => 'Tidak Kawin'
        ]);
    }
}

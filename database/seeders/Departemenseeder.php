<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Departemenseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departemen::create([
            'kode_dept' => 'IT',
            'nama_dept' => 'Information Technology',
        ]);
    }
}

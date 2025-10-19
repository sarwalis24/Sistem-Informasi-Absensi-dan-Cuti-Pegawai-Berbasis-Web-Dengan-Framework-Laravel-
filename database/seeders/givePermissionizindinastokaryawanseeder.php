<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class givePermissionizindinastokaryawanseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'karyawan')->first();
        $role->givePermissionTo('izindinas.create');
        $role->givePermissionTo('izindinas.delete');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AktivitasKaryawanPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Aktivitas Karyawan'
        ]);

        Permission::create([
            'name' => 'aktivitaskaryawan.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'aktivitaskaryawan.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'aktivitaskaryawan.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'aktivitaskaryawan.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        // Only give index permission to super admin
        $indexPermission = Permission::where('name', 'aktivitaskaryawan.index')->first();
        $roleID = 1; // Super Admin
        $role = Role::findById($roleID);
        $role->givePermissionTo($indexPermission);
    }
}

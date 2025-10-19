<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Presensipermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Presensi'
        ]);

        // Permission::create([
        //     'name' => 'presensi.index',
        //     'id_permission_group' => $permissiongroup->id
        // ]);

        Permission::create([
            'name' => 'presensi.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'presensi.edit',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'presensi.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $roleKaryawan = 3;
        $role = Role::findById($roleID);
        $rolekar = Role::findById($roleKaryawan);
        $role->givePermissionTo($permissions);
        $rolekar->givePermissionTo($permissions);
    }
}

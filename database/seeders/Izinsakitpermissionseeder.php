<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Izinsakitpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate(
            ['name' => 'Izin Sakit']
        );

        Permission::firstOrCreate(
            ['name' => 'izinsakit.index'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izinsakit.create'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izinsakit.edit'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izinsakit.delete'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izinsakit.approve'],
            ['id_permission_group' => $permissiongroup->id]
        );


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

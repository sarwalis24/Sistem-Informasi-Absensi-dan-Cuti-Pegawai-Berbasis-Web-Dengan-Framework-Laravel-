<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Izindinaspermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate(
            ['name' => 'Izin Dinas']
        );

        Permission::firstOrCreate(
            ['name' => 'izindinas.index'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izindinas.create'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izindinas.edit'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izindinas.delete'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izindinas.approve'],
            ['id_permission_group' => $permissiongroup->id]
        );


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

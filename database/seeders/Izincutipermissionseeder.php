<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Izincutipermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate(
            ['name' => 'Izin Cuti']
        );

        Permission::firstOrCreate(
            ['name' => 'izincuti.index'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izincuti.create'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izincuti.edit'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izincuti.delete'],
            ['id_permission_group' => $permissiongroup->id]
        );

        Permission::firstOrCreate(
            ['name' => 'izincuti.approve'],
            ['id_permission_group' => $permissiongroup->id]
        );


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

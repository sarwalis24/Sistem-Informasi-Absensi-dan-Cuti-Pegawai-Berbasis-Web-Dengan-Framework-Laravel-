<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Jamkerjapermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Jam Kerja'
        ]);

        Permission::create([
            'name' => 'jamkerja.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'jamkerja.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'jamkerja.edit',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'jamkerja.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'jamkerja.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        Permission::create([
            'name' => 'suratjalancabang.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

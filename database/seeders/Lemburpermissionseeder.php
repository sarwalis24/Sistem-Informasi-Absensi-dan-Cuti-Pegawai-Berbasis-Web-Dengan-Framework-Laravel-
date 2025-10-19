<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Lemburpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Lembur'
        ]);

        Permission::create([
            'name' => 'lembur.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'lembur.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'lembur.edit',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'lembur.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'lembur.approve',
            'id_permission_group' => $permissiongroup->id
        ]);





        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

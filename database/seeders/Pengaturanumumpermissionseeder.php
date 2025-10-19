<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Pengaturanumumpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'General Setting'
        ]);

        Permission::create([
            'name' => 'generalsetting.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'generalsetting.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'generalsetting.edit',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'generalsetting.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'generalsetting.delete',
            'id_permission_group' => $permissiongroup->id
        ]);




        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

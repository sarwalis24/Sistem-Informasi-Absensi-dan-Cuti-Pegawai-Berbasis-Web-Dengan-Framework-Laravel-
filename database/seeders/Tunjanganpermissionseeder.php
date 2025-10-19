<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Tunjanganpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Tunjangan'
        ]);

        Permission::create([
            'name' => 'tunjangan.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'tunjangan.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'tunjangan.edit',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'tunjangan.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'tunjangan.delete',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

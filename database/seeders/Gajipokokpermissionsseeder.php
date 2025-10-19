<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Gajipokokpermissionsseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Gaji Pokok'
        ]);

        Permission::create([
            'name' => 'gajipokok.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gajipokok.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gajipokok.edit',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'gajipokok.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gajipokok.delete',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

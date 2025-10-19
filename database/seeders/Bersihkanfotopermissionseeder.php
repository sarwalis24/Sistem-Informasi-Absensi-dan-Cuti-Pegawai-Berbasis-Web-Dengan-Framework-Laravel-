<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Bersihkanfotopermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Bersihkan Foto'
        ]);

        Permission::create([
            'name' => 'bersihkanfoto.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'bersihkanfoto.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1; // Super Admin
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

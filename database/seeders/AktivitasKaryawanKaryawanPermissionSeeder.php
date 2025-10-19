<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AktivitasKaryawanKaryawanPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the karyawan role
        $karyawanRole = Role::where('name', 'karyawan')->first();

        if ($karyawanRole) {
            // Get all aktivitas karyawan permissions
            $permissions = [
                'aktivitaskaryawan.index',
                'aktivitaskaryawan.create',
                'aktivitaskaryawan.edit',
                'aktivitaskaryawan.delete'
            ];

            // Assign permissions to karyawan role
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission && !$karyawanRole->hasPermissionTo($permission)) {
                    $karyawanRole->givePermissionTo($permission);
                    $this->command->info("Permission '{$permissionName}' assigned to karyawan role.");
                }
            }

            $this->command->info('All aktivitas karyawan permissions have been assigned to karyawan role.');
        } else {
            $this->command->error('Karyawan role not found. Please create the karyawan role first.');
        }
    }
}

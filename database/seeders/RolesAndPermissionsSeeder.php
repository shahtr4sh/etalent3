<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Permission utama untuk masuk modul admin
        $accessAdmin = Permission::firstOrCreate(['name' => 'access admin']);

        // Roles admin
        $roles = [
            'urusetia',
            'penyemak',
            'pelulus',
            'admin sistem',
            'pengurusan atasan',
        ];

        foreach ($roles as $r) {
            $role = Role::firstOrCreate(['name' => $r]);
            $role->givePermissionTo($accessAdmin);
        }

        // Pemohon tak perlu initialize sebagai role (auto-assigned)
    }
}

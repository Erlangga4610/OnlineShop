<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        Permission::query()->delete();
        Role::query()->delete();
        // Buat Permissions
        Permission::create(['name' => 'view product']);
        Permission::create(['name' => 'create product']);
        Permission::create(['name' => 'edit product']);
        Permission::create(['name' => 'delete product']);

        // Buat Roles
        $admin = Role::create(['name' => 'admin']);
        $kasir = Role::create(['name' => 'kasir']);

        // Assign permission ke role
        $admin->givePermissionTo(Permission::all());
        $kasir->givePermissionTo(['view product']);
    }
}

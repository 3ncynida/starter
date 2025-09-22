<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role
        $superAdminRole = Role::firstOrCreate(['name' => 'superAdmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $kasirRole = Role::firstOrCreate(['name' => 'kasir']);

        // Buat permissions
        $permissions = [
            'manage users',
            'manage roles',
            'manage permissions',
            'manage produk',
            'manage pelanggan',
            'view produk',
            'manage penjualan',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // buat super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superAdmin@email.com',
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole($superAdminRole);
        $superAdmin->givePermissionTo(['manage users', 'manage roles', 'manage permissions']);

        // Buat admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@email.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);
        $admin->givePermissionTo(['manage produk', 'manage pelanggan']);

        // Buat kasir
        $kasir = User::create([
            'name' => 'Regular Kasir',
            'email' => 'kasir@email.com',
            'password' => bcrypt('password'),
        ]);
        $kasir->assignRole($kasirRole);
        $kasir->givePermissionTo(['view produk', 'manage penjualan']);
    }
}

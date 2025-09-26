<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting; // ✅ tambahin ini
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ====== ROLE & PERMISSION ======
        $superAdminRole = Role::firstOrCreate(['name' => 'superAdmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $kasirRole = Role::firstOrCreate(['name' => 'kasir']);

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

        // ====== SUPER ADMIN ======
        $superAdmin = User::firstOrCreate(
            ['email' => 'superAdmin@email.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);
        $superAdmin->givePermissionTo(['manage users', 'manage roles', 'manage permissions']);

        // ====== ADMIN ======
        $admin = User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole($adminRole);
        $admin->givePermissionTo(['manage produk', 'manage pelanggan']);

        // ====== KASIR ======
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@email.com'],
            [
                'name' => 'Regular Kasir',
                'password' => bcrypt('password'),
            ]
        );
        $kasir->assignRole($kasirRole);
        $kasir->givePermissionTo(['view produk', 'manage penjualan']);

        // ====== SETTINGS DEFAULT ======
        Setting::firstOrCreate(
            ['key' => 'diskon_member'],
            ['value' => '10'] // default diskon 10%
        );
    }
}

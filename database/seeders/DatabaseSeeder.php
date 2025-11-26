<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\Setting;
use App\Models\User;
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
                'password' => bcrypt('123123123'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);
        $superAdmin->givePermissionTo(['manage users', 'manage roles', 'manage permissions']);

        // ====== ADMIN ======
        $admin = User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('123123123'),
            ]
        );
        $admin->assignRole($adminRole);
        $admin->givePermissionTo(['manage produk', 'manage pelanggan']);

        // ====== KASIR ======
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@email.com'],
            [
                'name' => 'Regular Kasir',
                'password' => bcrypt('123123123'),
            ]
        );
        $kasir->assignRole($kasirRole);
        $kasir->givePermissionTo(['view produk', 'manage penjualan', 'manage pelanggan']);

        // ====== SETTINGS DEFAULT ======
        Setting::firstOrCreate(
            ['key' => 'diskon_member'],
            ['value' => '5'] // default diskon 5%
        );

        // ====== PRODUK DEFAULT ======
        $products = [
            ['nama' => 'Power Ranger', 'harga' => 25000, 'stok' => 100],
            ['nama' => 'Ultraman', 'harga' => 20000, 'stok' => 150],
            ['nama' => 'Yoyo', 'harga' => 15000, 'stok' => 200],
            ['nama' => 'Gasing', 'harga' => 30000, 'stok' => 80],
        ];

        $pelanggan = \App\Models\Pelanggan::firstOrCreate(
            ['NamaPelanggan' => 'Budi'],
            ['Alamat' => 'Jl. Merdeka No. 1']
        );

        foreach ($products as $product) {
            Produk::firstOrCreate(
                ['NamaProduk' => $product['nama']],
                [
                    'Harga' => $product['harga'],
                    'Stok' => $product['stok'],
                ]
            );
        }
    }
}
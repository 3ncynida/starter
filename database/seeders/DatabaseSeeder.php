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
            ['value' => '10'] // default diskon 10%
        );

        // ====== PRODUK DEFAULT ======
        $products = [
            ['nama' => 'Apel Fuji', 'harga' => 25000, 'stok' => 100, 'satuan' => 'kg'],
            ['nama' => 'Jeruk Mandarin', 'harga' => 20000, 'stok' => 150, 'satuan' => 'kg'],
            ['nama' => 'Pisang Cavendish', 'harga' => 15000, 'stok' => 200, 'satuan' => 'sisir'],
            ['nama' => 'Mangga Harum Manis', 'harga' => 30000, 'stok' => 80, 'satuan' => 'kg'],
            ['nama' => 'Anggur Merah', 'harga' => 45000, 'stok' => 50, 'satuan' => 'kg'],
            ['nama' => 'Semangka Merah', 'harga' => 18000, 'stok' => 30, 'satuan' => 'buah'],
            ['nama' => 'Nanas Madu', 'harga' => 12000, 'stok' => 60, 'satuan' => 'buah'],
            ['nama' => 'Pir Ya Lie', 'harga' => 35000, 'stok' => 75, 'satuan' => 'kg'],
            ['nama' => 'Melon Honey Dew', 'harga' => 22000, 'stok' => 40, 'satuan' => 'buah'],
            ['nama' => 'Strawberry Fresh', 'harga' => 40000, 'stok' => 45, 'satuan' => 'pack'],
            ['nama' => 'Kiwi Hijau', 'harga' => 50000, 'stok' => 35, 'satuan' => 'kg'],
            ['nama' => 'Pepaya California', 'harga' => 16000, 'stok' => 55, 'satuan' => 'buah'],
            ['nama' => 'Jambu Kristal', 'harga' => 28000, 'stok' => 65, 'satuan' => 'kg'],
            ['nama' => 'Salak Pondoh', 'harga' => 15000, 'stok' => 90, 'satuan' => 'kg'],
            ['nama' => 'Duku Palembang', 'harga' => 25000, 'stok' => 70, 'satuan' => 'kg'],
            ['nama' => 'Rambutan Binjai', 'harga' => 18000, 'stok' => 85, 'satuan' => 'kg'],
            ['nama' => 'Durian Montong', 'harga' => 75000, 'stok' => 25, 'satuan' => 'buah'],
            ['nama' => 'Alpukat Mentega', 'harga' => 32000, 'stok' => 60, 'satuan' => 'kg'],
            ['nama' => 'Manggis Super', 'harga' => 28000, 'stok' => 75, 'satuan' => 'kg'],
            ['nama' => 'Lemon Import', 'harga' => 45000, 'stok' => 40, 'satuan' => 'kg'],
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
                    'Satuan' => $product['satuan'],
                ]
            );
        }
    }
}

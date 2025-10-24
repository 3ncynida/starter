<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id('DetailID');

            // Masih tetap relasi ke penjualan (biar tahu ini milik penjualan mana)
            $table->foreignId('PenjualanID')
                ->constrained('penjualan', 'PenjualanID')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // 🧾 Data produk disimpan sebagai string & angka (bukan ID)
            $table->string('produk_nama');
            $table->decimal('produk_harga_asli', 10, 2);
            $table->decimal('produk_harga_jual', 10, 2);

            // 🎯 Informasi diskon promo
            $table->decimal('diskon_promo_persen', 5, 2)->nullable();
            $table->decimal('diskon_promo_nominal', 10, 2)->nullable();

            // 📦 Jumlah & subtotal
            $table->integer('jumlah');
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};

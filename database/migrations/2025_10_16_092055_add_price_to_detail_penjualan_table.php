<?php

// database/migrations/2025_10_16_add_price_to_detail_penjualan.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            // harga per unit disimpan saat transaksi
            $table->decimal('Harga', 14, 2)->after('ProdukID')->default(0);
            // diskon promo nominal per unit (jika ada)
            $table->decimal('DiskonPromoNominal', 14, 2)->after('Harga')->nullable();
            // persen diskon promo saat transaksi (optional, buat informasi)
            $table->decimal('DiskonPromoPersen', 5, 2)->after('DiskonPromoNominal')->nullable();
            // (subtotal tetap ada)
        });
    }

    public function down(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropColumn(['Harga', 'DiskonPromoNominal', 'DiskonPromoPersen']);
        });
    }
};

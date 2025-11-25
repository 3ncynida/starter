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
            
            // Relasi ke Penjualan
            $table->foreignId('PenjualanID')
            ->constrained('penjualan', 'PenjualanID')
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
            
            // Relasi ke Produk
            $table->unsignedBigInteger('ProdukID')->nullable();
            $table->foreign('ProdukID')
                ->references('ProdukID')
                ->on('produk')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->string('NamaProduk');
            // harga per unit disimpan saat transaksi
            $table->decimal('Harga', 14, 2)->default(0);
            // diskon promo nominal per unit (jika ada)
            $table->decimal('DiskonPromoNominal', 14, 2)->nullable();
            // persen diskon promo saat transaksi (optional, buat informasi)
            $table->decimal('DiskonPromoPersen', 5, 2)->nullable();
            // (subtotal tetap ada)
            
            $table->integer('JumlahProduk');
            $table->decimal('Subtotal', 10, 2);
            $table->timestamps();   
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};

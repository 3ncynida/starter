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

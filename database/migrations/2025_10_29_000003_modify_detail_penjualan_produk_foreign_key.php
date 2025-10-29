<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            // Hapus foreign key yang lama
            $table->dropForeign(['ProdukID']);
            
            // Buat foreign key baru dengan SET NULL on delete
            $table->foreign('ProdukID')
                ->references('ProdukID')
                ->on('produk')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            // Hapus foreign key yang baru
            $table->dropForeign(['ProdukID']);
            
            // Kembalikan ke foreign key yang lama
            $table->foreign('ProdukID')
                ->references('ProdukID')
                ->on('produk')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
};
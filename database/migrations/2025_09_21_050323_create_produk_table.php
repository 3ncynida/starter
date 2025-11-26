<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('ProdukID');
            $table->string('NamaProduk', 255);
            $table->decimal('Harga', 10, 2);
            $table->integer('Stok');
            // field untuk promo produk
            $table->boolean('Promosi')->default(false); // true = produk sedang promo
            $table->decimal('DiskonPersen', 5, 2)->nullable(); // misal 10.00 = 10%
            $table->date('TanggalMulaiPromosi')->nullable();
            $table->date('TanggalSelesaiPromosi')->nullable();
            $table->string('Gambar')->default('produk/default.png');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};

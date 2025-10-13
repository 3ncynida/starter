<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // field untuk promo produk
            $table->boolean('Promosi')->default(false)->after('Stok'); // true = produk sedang promo
            $table->decimal('DiskonPersen', 5, 2)->nullable()->after('Promosi'); // misal 10.00 = 10%
            $table->date('TanggalMulaiPromosi')->nullable()->after('DiskonPersen');
            $table->date('TanggalSelesaiPromosi')->nullable()->after('TanggalMulaiPromosi');
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['Promosi', 'DiskonPersen', 'TanggalMulaiPromosi', 'TanggalSelesaiPromosi']);
        });
    }
};

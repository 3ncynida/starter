<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->decimal('UangTunai', 15, 2)->nullable()->after('TotalHarga');
            $table->decimal('Kembalian', 15, 2)->nullable()->after('UangTunai');
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['UangTunai', 'Kembalian']);
        });
    }
};


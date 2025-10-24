<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            // 🔹 Hapus foreign key constraint dulu
            $table->dropForeign(['PelangganID']);

            // 🔹 Hapus kolom PelangganID
            $table->dropColumn('PelangganID');

            // 🔹 Tambah kolom NamaPelanggan
            if (!Schema::hasColumn('penjualan', 'NamaPelanggan')) {
                $table->string('NamaPelanggan')->nullable()->after('PenjualanID');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->unsignedBigInteger('PelangganID')->nullable()->after('PenjualanID');
            $table->foreign('PelangganID')
                ->references('PelangganID')
                ->on('pelanggan')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->dropColumn('NamaPelanggan');
        });
    }
};

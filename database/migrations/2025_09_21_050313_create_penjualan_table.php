<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('PenjualanID');
            $table->date('TanggalPenjualan');
            $table->decimal('TotalHarga', 10, 2)->default(0);
            $table->decimal('Diskon', 10, 2)->default(0);

            // Relasi ke Pelanggan
            $table->foreignId('PelangganID')
                ->nullable()
                ->constrained('pelanggan', 'PelangganID')
                ->cascadeOnUpdate()
                ->nullOnDelete();
                
    // Relasi ke User (Kasir)
    $table->foreignId('user_id')
        ->nullable() // ⛔ WAJIB! Karena onDelete('set null')
        ->constrained('users', 'id')
        ->cascadeOnUpdate()
        ->nullOnDelete();

                $table->timestamps();
                
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};

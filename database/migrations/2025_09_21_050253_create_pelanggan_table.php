<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('PelangganID');
            $table->string('NamaPelanggan', 255);
            $table->text('Alamat')->nullable();
            $table->string('NomorTelepon', 15)->nullable();
            $table->timestamp('member_start')->nullable();
            $table->timestamp('member_expired')->nullable();
            $table->boolean('is_member')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};

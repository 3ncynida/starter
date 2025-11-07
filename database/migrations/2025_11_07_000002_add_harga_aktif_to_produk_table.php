<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->decimal('harga_aktif', 10, 2)->nullable()->after('Harga');
        });

        // Update harga_aktif dengan nilai Harga saat ini
        DB::statement('UPDATE produk SET harga_aktif = Harga');
    }

    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('harga_aktif');
        });
    }
};
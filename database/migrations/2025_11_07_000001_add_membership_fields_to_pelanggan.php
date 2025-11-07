<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->timestamp('member_start')->nullable()->after('NomorTelepon');
            $table->timestamp('member_expired')->nullable()->after('member_start');
            $table->boolean('is_member')->default(false)->after('member_expired');
        });
    }

    public function down()
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn(['member_start', 'member_expired', 'is_member']);
        });
    }
};
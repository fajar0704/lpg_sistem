<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_lpg', function (Blueprint $table) {
            $table->integer('safety_stock')->default(10)->after('stock_out');
        });
    }

    public function down(): void
    {
        Schema::table('stock_lpg', function (Blueprint $table) {
            $table->dropColumn('safety_stock');
        });
    }
};

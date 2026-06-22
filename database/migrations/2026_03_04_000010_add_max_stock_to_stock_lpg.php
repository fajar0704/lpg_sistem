<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_lpg', function (Blueprint $table) {
            $table->integer('max_stock')->default(0)->after('safety_stock');
        });
    }

    public function down(): void
    {
        Schema::table('stock_lpg', function (Blueprint $table) {
            $table->dropColumn('max_stock');
        });
    }
};

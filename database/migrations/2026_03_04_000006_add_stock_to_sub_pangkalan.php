<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_pangkalan', function (Blueprint $table) {
            $table->integer('stok_isi')->default(0)->after('is_active');
            $table->integer('stok_kosong')->default(0)->after('stok_isi');
        });

        Schema::table('distributions', function (Blueprint $table) {
            // transaction_type: receive=terima dari pangkalan, sell=jual ke pelanggan, exchange=tukar kosong
            $table->enum('transaction_type', ['receive', 'sell', 'exchange'])->default('receive')->after('type');
            $table->enum('customer_type', ['rumah_tangga', 'usaha'])->nullable()->after('transaction_type');
        });
    }

    public function down(): void
    {
        Schema::table('sub_pangkalan', function (Blueprint $table) {
            $table->dropColumn(['stok_isi', 'stok_kosong']);
        });
        Schema::table('distributions', function (Blueprint $table) {
            $table->dropColumn(['transaction_type', 'customer_type']);
        });
    }
};

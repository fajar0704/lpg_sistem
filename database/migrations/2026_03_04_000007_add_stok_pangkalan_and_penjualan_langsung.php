<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah stok_isi & stok_kosong ke stok pangkalan
        Schema::table('stock_lpg', function (Blueprint $table) {
            $table->integer('stok_isi')->default(0)->after('current_stock');
            $table->integer('stok_kosong')->default(0)->after('stok_isi');
        });

        // Tabel penjualan langsung ke pembeli di pangkalan
        Schema::create('penjualan_langsung', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // admin
            $table->string('tabung_type');
            $table->integer('quantity');
            $table->enum('customer_type', ['rumah_tangga', 'usaha_mikro']);
            $table->string('nama_pembeli')->nullable();
            $table->date('transaction_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('stock_lpg', function (Blueprint $table) {
            $table->dropColumn(['stok_isi', 'stok_kosong']);
        });
        Schema::dropIfExists('penjualan_langsung');
    }
};

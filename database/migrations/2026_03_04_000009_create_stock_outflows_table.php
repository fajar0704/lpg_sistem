<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_outflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_batch_id')->constrained('stock_batches')->onDelete('cascade');
            $table->string('tabung_type');
            $table->integer('quantity');
            $table->date('transaction_date');
            $table->enum('source', ['penjualan_langsung', 'distribusi_sub'])->default('penjualan_langsung');
            $table->nullableMorphs('sourceable'); // polymorphic ke penjualan_langsung atau distributions
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_outflows');
    }
};

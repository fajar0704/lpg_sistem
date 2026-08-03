<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('sub_pangkalan_id')->nullable()->after('id')->constrained('sub_pangkalan')->nullOnDelete();
        });

        Schema::table('distributions', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('sub_pangkalan_id')->constrained('customers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributions', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['sub_pangkalan_id']);
            $table->dropColumn('sub_pangkalan_id');
        });
    }
};

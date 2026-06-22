<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'sub_pangkalan'])->default('sub_pangkalan');
            $table->foreignId('sub_pangkalan_id')->nullable()->constrained('sub_pangkalan')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sub_pangkalan_id']);
            $table->dropColumn(['role', 'sub_pangkalan_id', 'is_active']);
        });
    }
};

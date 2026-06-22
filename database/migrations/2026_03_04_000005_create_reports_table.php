<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['daily', 'monthly', 'sub_pangkalan']);
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('sub_pangkalan_id')->nullable()->constrained('sub_pangkalan')->onDelete('set null');
            $table->string('file_path')->nullable();
            $table->enum('format', ['pdf', 'excel'])->default('pdf');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

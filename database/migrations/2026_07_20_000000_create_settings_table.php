<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default values
        DB::table('settings')->insert([
            ['key' => 'login_logo', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'login_title', 'value' => 'Sistem Pangkalan LPG', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'login_subtitle', 'value' => 'Silakan masuk untuk mengelola LPG', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

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
        Schema::table('sub_pangkalan', function (Blueprint $table) {
            $table->string('nama_ktp')->nullable()->after('ktp');
            $table->string('tempat_lahir')->nullable()->after('nama_ktp');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('tanggal_lahir');
            $table->text('alamat_ktp')->nullable()->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_pangkalan', function (Blueprint $table) {
            $table->dropColumn(['nama_ktp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat_ktp']);
        });
    }
};

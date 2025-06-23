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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru untuk menyimpan path foto
            // 'nullable' artinya kolom ini boleh kosong (user tidak wajib upload foto)
            // 'after' menempatkan kolom ini setelah kolom 'password' agar rapi
            $table->string('profile_photo_path', 2048)->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika migrasi dibatalkan
            $table->dropColumn('profile_photo_path');
        });
    }
};

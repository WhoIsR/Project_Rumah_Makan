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
        Schema::table('menu_items', function (Blueprint $table) {
        // Tambahkan kolom category_id yang terhubung ke tabel categories
        // `after('price')` artinya kolom ini akan dibuat setelah kolom 'price'
        // `nullable()` artinya menu boleh tidak punya kategori untuk sementara
        // `constrained()` membuat foreign key constraint
        $table->foreignId('category_id')->nullable()->after('price')->constrained()->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            //
        });
    }
};

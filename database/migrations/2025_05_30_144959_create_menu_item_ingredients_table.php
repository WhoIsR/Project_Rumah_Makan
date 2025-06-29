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
        Schema::create('menu_item_ingredients', function (Blueprint $table) {
            // Kunci utama gabungan untuk mencegah duplikasi
            $table->primary(['menu_item_id', 'ingredient_id']);

            // Foreign key ke tabel menu_items
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');

            // Foreign key ke tabel ingredients
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');

            // Kolom untuk menyimpan jumlah bahan yang dibutuhkan
            $table->decimal('quantity_needed', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_ingredients');
    }
};

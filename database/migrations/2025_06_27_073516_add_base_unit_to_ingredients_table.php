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
        Schema::table('ingredients', function (Blueprint $table) {
            // Hapus kolom 'unit' yang lama jika masih ada
            if (Schema::hasColumn('ingredients', 'unit')) {
                $table->dropColumn('unit');
            }
            // Hapus kolom 'supplier_id' karena sudah dipindah ke transaksi
            if (Schema::hasColumn('ingredients', 'supplier_id')) {
                $table->dropForeign(['supplier_id']);
                $table->dropColumn('supplier_id');
            }
            
            // Tambahkan foreign key ke tabel units sebagai satuan dasar penyimpanan stok
            $table->foreignId('base_unit_id')->after('stock')->constrained('units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropForeign(['base_unit_id']);
            $table->dropColumn('base_unit_id');
            $table->string('unit')->after('stock')->nullable(); // Kembalikan kolom lama jika rollback
        });
    }
};
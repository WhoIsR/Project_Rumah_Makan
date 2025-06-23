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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->unique()->nullable(); // Kolom email tetap ada
            $table->timestamps();
        });

        // Tambahkan foreign key supplier_id ke tabel ingredients
        Schema::table('ingredients', function (Blueprint $table) {
            // Cek jika kolom sudah ada untuk menghindari error saat rollback atau migrate:fresh
            if (!Schema::hasColumn('ingredients', 'supplier_id')) {
                $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pastikan kolom ada sebelum menghapus
        Schema::table('ingredients', function (Blueprint $table) {
            if (Schema::hasColumn('ingredients', 'supplier_id')) {
                $table->dropForeign(['supplier_id']);
                $table->dropColumn('supplier_id');
            }
        });

        Schema::dropIfExists('suppliers');
    }
};
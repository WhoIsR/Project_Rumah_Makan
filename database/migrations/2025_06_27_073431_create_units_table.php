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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nama unit, cth: gram, kilogram, milliliter, liter, pcs');
            $table->string('symbol')->unique()->comment('Simbol unit, cth: g, kg, ml, L, pcs');
            $table->foreignId('base_unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->decimal('conversion_factor', 15, 5)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
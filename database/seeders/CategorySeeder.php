<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints(); // Matikan pengecekan foreign key

        DB::table('categories')->truncate(); // Kosongkan tabel

        Schema::enableForeignKeyConstraints(); // Nyalakan kembali

        // Buat beberapa kategori contoh
        Category::create(['name' => 'Makanan']);
        Category::create(['name' => 'Minuman']);
        Category::create(['name' => 'Cemilan']);
    }
}

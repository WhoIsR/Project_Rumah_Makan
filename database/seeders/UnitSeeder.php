<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints(); // Matikan pengecekan foreign key

        DB::table('units')->truncate(); // Kosongkan tabel

        Schema::enableForeignKeyConstraints(); // Nyalakan kembali

        // Buat unit dasar dulu
        $gram = Unit::create(['name' => 'Gram', 'symbol' => 'g', 'base_unit_id' => null, 'conversion_factor' => 1]);
        $ml = Unit::create(['name' => 'Milliliter', 'symbol' => 'ml', 'base_unit_id' => null, 'conversion_factor' => 1]);
        $pcs = Unit::create(['name' => 'Pieces', 'symbol' => 'pcs', 'base_unit_id' => null, 'conversion_factor' => 1]);

        // Buat unit turunan
        Unit::create(['name' => 'Kilogram', 'symbol' => 'kg', 'base_unit_id' => $gram->id, 'conversion_factor' => 1000]);
        Unit::create(['name' => 'Liter', 'symbol' => 'L', 'base_unit_id' => $ml->id, 'conversion_factor' => 1000]);
    }
}

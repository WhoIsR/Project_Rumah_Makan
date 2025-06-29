<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan Faker untuk data Indonesia

        // Daftar nama pasar yang bisa digunakan
        $marketNames = [
            'Pasar Tradisional Indah',
            'Pasar Jaya Sentosa',
            'Pasar Kaget Bahagia',
            'Pasar Pagi Makmur',
            'Pasar Raya Sejahtera',
            'Pasar Tradisional Indah', // Ulangi untuk simulasi pedagang berbeda di pasar yang sama
            'Pasar Jaya Sentosa',     // Ulangi
            'Pasar Kaget Bahagia',    // Ulangi
        ];

        // Buat 10 data supplier
        for ($i = 0; $i < 10; $i++) {
            Supplier::create([
                'name' => $faker->randomElement($marketNames), // Pilih nama pasar dari daftar
                'contact_person' => $faker->name, // Nama pedagang
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'email' => $faker->unique()->safeEmail,
            ]);
        }
    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KategoriPegawaiSeeder::class,
            StatusKepegawaianSeeder::class,
            UnitKerjaSeeder::class,
            BidangSeeder::class,
            FormasiJabatanSeeder::class,
            PegawaiSeeder::class,
            AdminSeeder::class,
        ]);
    }
}

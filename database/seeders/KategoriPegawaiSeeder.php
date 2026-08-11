<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriPegawai;

class KategoriPegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = [
            'PNS',
            'PPPK',
            'PPPK Paruh Waktu',
            'Swakelola',
            'Outsourcing',
        ];

        foreach ($kategoriList as $nama) {
            KategoriPegawai::firstOrCreate(['nama' => $nama]);
        }
    }
}

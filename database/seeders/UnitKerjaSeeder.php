<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitKerja;

class UnitKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['nama' => 'Dinas Pangan dan Pertanian (Induk)', 'tipe' => 'Dinas Induk'],
            ['nama' => 'UPTD Rumah Potong Hewan dan Pasar Hewan', 'tipe' => 'UPTD'],
            ['nama' => 'UPTD Laboratorium Kesehatan Hewan dan Kesmavet', 'tipe' => 'UPTD'],
        ];

        foreach ($units as $u) {
            UnitKerja::firstOrCreate(['nama' => $u['nama']], $u);
        }
    }
}

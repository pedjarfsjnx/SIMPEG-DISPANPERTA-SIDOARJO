<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitKerja;
use App\Models\Bidang;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $dinasInduk = UnitKerja::where('tipe', 'Dinas Induk')->first();

        if ($dinasInduk) {
            $bidangList = [
                'Sekretariat',
                'Bidang Produksi Peternakan',
                'Bidang Produksi Tanaman Pangan dan Hortikultura',
                'Bidang Sarana Prasarana dan Penyuluhan Pertanian',
                'Bidang Ketahanan Pangan',
            ];

            foreach ($bidangList as $nama) {
                Bidang::firstOrCreate([
                    'unit_kerja_id' => $dinasInduk->id,
                    'nama' => $nama,
                ]);
            }
        }
    }
}

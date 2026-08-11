<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitKerja;
use App\Models\Bidang;
use App\Models\FormasiJabatan;

class FormasiJabatanSeeder extends Seeder
{
    public function run(): void
    {
        $dinasInduk = UnitKerja::where('tipe', 'Dinas Induk')->first();
        $uptdRph = UnitKerja::where('nama', 'LIKE', '%RPH%')->first();

        $sekretariat = Bidang::where('nama', 'Sekretariat')->first();
        $bidangPeternakan = Bidang::where('nama', 'LIKE', '%Peternakan%')->first();

        $formasiData = [
            [
                'unit_kerja_id' => $dinasInduk?->id ?? 1,
                'bidang_id' => null,
                'nama_jabatan' => 'KEPALA DINAS PANGAN DAN PERTANIAN',
                'kelas_jabatan' => '14',
                'status_formasi' => 'terisi',
            ],
            [
                'unit_kerja_id' => $dinasInduk?->id ?? 1,
                'bidang_id' => $sekretariat?->id,
                'nama_jabatan' => 'SEKRETARIS DINAS',
                'kelas_jabatan' => '12',
                'status_formasi' => 'terisi',
            ],
            [
                'unit_kerja_id' => $dinasInduk?->id ?? 1,
                'bidang_id' => $bidangPeternakan?->id,
                'nama_jabatan' => 'KEPALA BIDANG PRODUKSI PETERNAKAN',
                'kelas_jabatan' => '11',
                'status_formasi' => 'terisi',
            ],
            [
                'unit_kerja_id' => $uptdRph?->id ?? 2,
                'bidang_id' => null,
                'nama_jabatan' => 'KEPALA SUB BAGIAN TATA USAHA UPTD RPH DAN PASAR HEWAN',
                'kelas_jabatan' => '9',
                'status_formasi' => 'kosong', // Formasi kosong sample
            ],
        ];

        foreach ($formasiData as $data) {
            FormasiJabatan::firstOrCreate(
                ['nama_jabatan' => $data['nama_jabatan'], 'unit_kerja_id' => $data['unit_kerja_id']],
                $data
            );
        }
    }
}

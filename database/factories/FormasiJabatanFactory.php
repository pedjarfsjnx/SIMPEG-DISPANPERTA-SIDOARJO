<?php

namespace Database\Factories;

use App\Models\FormasiJabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormasiJabatanFactory extends Factory
{
    protected $model = FormasiJabatan::class;

    public function definition(): array
    {
        return [
            'unit_kerja_id' => UnitKerjaFactory::new()->create()->id,
            'bidang_id' => null,
            'nama_jabatan' => 'Analis Kebijakan Ahli Pertama',
            'kelas_jabatan' => '9',
            'kuota' => 1,
            'status_formasi' => 'kosong',
            'aktif' => true,
        ];
    }
}

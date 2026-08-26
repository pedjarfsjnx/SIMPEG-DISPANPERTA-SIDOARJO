<?php

namespace Database\Factories;

use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitKerjaFactory extends Factory
{
    protected $model = UnitKerja::class;

    public function definition(): array
    {
        return [
            'nama' => 'Dinas Pangan dan Pertanian',
            'tipe' => 'Dinas Induk',
            'aktif' => true,
        ];
    }
}

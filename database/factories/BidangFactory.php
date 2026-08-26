<?php

namespace Database\Factories;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidangFactory extends Factory
{
    protected $model = Bidang::class;

    public function definition(): array
    {
        return [
            'unit_kerja_id' => UnitKerjaFactory::new()->create()->id,
            'nama' => 'Bidang Tanaman Pangan',
            'aktif' => true,
        ];
    }
}

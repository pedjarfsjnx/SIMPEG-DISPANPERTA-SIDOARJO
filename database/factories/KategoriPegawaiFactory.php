<?php

namespace Database\Factories;

use App\Models\KategoriPegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriPegawaiFactory extends Factory
{
    protected $model = KategoriPegawai::class;

    public function definition(): array
    {
        return ['nama' => $this->faker->unique()->word()];
    }

    public static function pns(): KategoriPegawai
    {
        return KategoriPegawai::firstOrCreate(['nama' => 'PNS']);
    }

    public static function pppk(): KategoriPegawai
    {
        return KategoriPegawai::firstOrCreate(['nama' => 'PPPK']);
    }

    public static function honorer(): KategoriPegawai
    {
        return KategoriPegawai::firstOrCreate(['nama' => 'Honorer']);
    }
}

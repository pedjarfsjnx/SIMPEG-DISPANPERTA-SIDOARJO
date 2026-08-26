<?php

namespace Database\Factories;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class PegawaiFactory extends Factory
{
    protected $model = Pegawai::class;

    public function definition(): array
    {
        return [
            'kategori_pegawai_id' => KategoriPegawaiFactory::pns()->id,
            'status_kepegawaian_id' => StatusKepegawaianFactory::aktif()->id,
            'unit_kerja_id' => UnitKerjaFactory::new()->create()->id,
            'bidang_id' => null,
            'formasi_jabatan_id' => null,
            'nama' => $this->faker->name(),
            'nip' => $this->faker->unique()->numerify('19850312201001100#'),
            'nik' => $this->faker->unique()->numerify('35############'),
            'tempat_lahir' => 'Sidoarjo',
            'tanggal_lahir' => '1990-05-15',
            'pendidikan' => 'S1',
            'golongan' => 'III/c',
            'no_hp' => '081234567890',
            'email' => $this->faker->unique()->safeEmail(),
            'tmt_jabatan' => '2020-01-01',
        ];
    }

    public function pns(): static
    {
        return $this->state(fn () => [
            'kategori_pegawai_id' => KategoriPegawaiFactory::pns()->id,
            'golongan' => 'III/c',
        ]);
    }

    public function pppk(): static
    {
        return $this->state(fn () => [
            'kategori_pegawai_id' => KategoriPegawaiFactory::pppk()->id,
            'golongan' => null,
        ]);
    }

    public function honorer(): static
    {
        return $this->state(fn () => [
            'kategori_pegawai_id' => KategoriPegawaiFactory::honorer()->id,
            'golongan' => null,
            'nip' => null,
            'tmt_jabatan' => null,
        ]);
    }
}

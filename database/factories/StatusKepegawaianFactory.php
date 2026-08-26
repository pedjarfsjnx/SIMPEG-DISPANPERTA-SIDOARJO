<?php

namespace Database\Factories;

use App\Models\StatusKepegawaian;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusKepegawaianFactory extends Factory
{
    protected $model = StatusKepegawaian::class;

    public function definition(): array
    {
        return ['nama' => $this->faker->unique()->word()];
    }

    public static function aktif(): StatusKepegawaian
    {
        return StatusKepegawaian::firstOrCreate(['nama' => 'Aktif']);
    }
}

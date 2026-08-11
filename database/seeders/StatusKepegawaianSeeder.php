<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusKepegawaian;

class StatusKepegawaianSeeder extends Seeder
{
    public function run(): void
    {
        $statusList = [
            'Aktif',
            'Pensiun',
            'Mutasi',
            'Cuti',
            'Nonaktif',
        ];

        foreach ($statusList as $nama) {
            StatusKepegawaian::firstOrCreate(['nama' => $nama]);
        }
    }
}

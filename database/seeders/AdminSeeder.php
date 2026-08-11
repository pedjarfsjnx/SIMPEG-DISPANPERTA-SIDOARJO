<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@dispanperta.sidoarjo.go.id'],
            [
                'name' => 'Admin Kepegawaian',
                'email' => 'admin@dispanperta.sidoarjo.go.id',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}

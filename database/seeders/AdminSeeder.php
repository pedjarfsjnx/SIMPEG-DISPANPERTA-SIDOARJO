<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Note: User model has 'password' => 'hashed' in casts, so pass plain text password to avoid double-hashing
        User::updateOrCreate(
            ['email' => 'admin@dispanperta.sidoarjo.go.id'],
            [
                'name' => 'Admin Kepegawaian',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
    }
}

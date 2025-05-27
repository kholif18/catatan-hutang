<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'username' => 'admin', // pastikan kolom username sudah ada
                'password' => Hash::make('admin'), // ganti dengan password aman
                'email_verified_at' => now(),
                'role' => 'admin',
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin Akar Kelana',
            'email' => 'admin@akarkelana.com', // Ganti dengan email keinginanmu
            'password' => Hash::make('admin123'), // Ganti dengan password yang kuat
            'role' => 'admin', // Pastikan kolom role di tabel users kamu namanya 'role'
        ]);
    }
}

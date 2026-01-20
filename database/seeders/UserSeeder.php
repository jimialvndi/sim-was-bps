<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Admin BPS',
            'email' => 'admin@bps.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nip' => '199001012020121001',
            'jabatan' => 'Administrator'
        ]);

        // 2. Akun Ketua
        User::create([
            'name' => 'Pak Kepala BPS',
            'email' => 'ketua@bps.go.id',
            'password' => Hash::make('password'),
            'role' => 'ketua',
            'nip' => '198001012010121002',
            'jabatan' => 'Kepala BPS Kabupaten'
        ]);

        // 3. Akun Pengawas
        User::create([
            'name' => 'Jimi Pengawas',
            'email' => 'pengawas@bps.go.id',
            'password' => Hash::make('password'),
            'role' => 'pengawas',
            'nip' => '199501012022121003',
            'jabatan' => 'Mitra Statistik'
        ]);
    }
}
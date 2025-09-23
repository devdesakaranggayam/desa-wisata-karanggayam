<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $username = $this->command->ask('Masukkan username admin');
        $nama     = $this->command->ask('Masukkan nama admin');
        $email    = $this->command->ask('Masukkan email admin');
        $nomorHp  = $this->command->ask('Masukkan nomor HP admin');
        $password = $this->command->secret('Masukkan password admin (hidden)');

        Admin::create([
            'username'          => $username,
            'nama'              => $nama,
            'email'             => $email,
            'nomor_hp'          => $nomorHp,
            'email_verified_at' => now(),
            'password'          => $password,
            'remember_token'    => Str::random(10),
            'is_superadmin'     => true
        ]);
    }
}

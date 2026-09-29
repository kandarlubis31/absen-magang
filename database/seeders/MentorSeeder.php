<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MentorSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Bapak Atta',
            'nim' => 'mr.atta001', // NIM bisa kita buat unik untuk mentor
            'email' => 'mentor@absen.com',
            'password' => Hash::make('atta12345'), // Pastikan untuk meng-hash password
            'role' => 'mentor',
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Magang;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Data Mahasiswa 1
        $mahasiswa1 = User::create([
            'name' => 'Budi Sudarsono',
            'nim' => '15220717',
            'email' => 'budi@gmail.com',
            'jurusan' => 'Teknik Informatika',
            'universitas' => 'Universitas Teknologi Canggih',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Magang::create([
            'id_mahasiswa' => $mahasiswa1->id,
            'mulai_magang' => Carbon::now()->startOfMonth(),
            'selesai_magang' => Carbon::now()->startOfMonth()->addMonths(3),
            'status' => 'aktif',
        ]);

        // Data Mahasiswa 2
        $mahasiswa2 = User::create([
            'name' => 'Citra Lestari',
            'nim' => '15220718',
            'email' => 'citra@gmail.com',
            'jurusan' => 'Sistem Informasi',
            'universitas' => 'Universitas Teknologi Canggih',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Magang::create([
            'id_mahasiswa' => $mahasiswa2->id,
            'mulai_magang' => Carbon::now()->startOfMonth(),
            'selesai_magang' => Carbon::now()->startOfMonth()->addMonths(3),
            'status' => 'aktif',
        ]);
        
        // Data Mahasiswa 3
        $mahasiswa3 = User::create([
            'name' => 'Doni Saputra',
            'nim' => '15220719',
            'email' => 'doni@gmail.com',
            'jurusan' => 'Teknik Komputer',
            'universitas' => 'Universitas Teknologi Canggih',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        Magang::create([
            'id_mahasiswa' => $mahasiswa3->id,
            'mulai_magang' => Carbon::now()->startOfMonth(),
            'selesai_magang' => Carbon::now()->startOfMonth()->addMonths(3),
            'status' => 'aktif',
        ]);
    }
}
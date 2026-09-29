<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class RekapSemuaMahasiswaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $mahasiswas;

    public function __construct(Collection $mahasiswas)
    {
        $this->mahasiswas = $mahasiswas;
    }

    public function collection(): Collection
    {
        return $this->mahasiswas;
    }

    public function headings(): array
    {
        return [
            'Nama Mahasiswa',
            'NIM',
            'Jurusan',
            'Universitas', // Kolom baru
            'Total Hadir',
        ];
    }

    public function map($mahasiswa): array
    {
        $totalHadir = 0;
        if ($mahasiswa->magang) {
            $totalHadir = $mahasiswa->magang->absensi->where('status', 'hadir')->count();
        }

        return [
            $mahasiswa->name,
            $mahasiswa->nim,
            $mahasiswa->jurusan ?? '-',
            $mahasiswa->universitas ?? '-', 
            $totalHadir,
        ];
    }
}
<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Carbon\Carbon;

class AbsensiMahasiswaExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $mahasiswa;
    protected $semuaAbsensi;
    protected $totalHadir;

    public function __construct(User $mahasiswa, Collection $semuaAbsensi, int $totalHadir)
    {
        $this->mahasiswa    = $mahasiswa;
        $this->semuaAbsensi = $semuaAbsensi;
        $this->totalHadir   = $totalHadir;
    }

    public function collection(): Collection
    {
        return $this->semuaAbsensi;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Keterangan',
        ];
    }

    public function map($absensi): array
    {
        return [
            Carbon::parse($absensi->tanggal)->translatedFormat('d F Y'),
            $absensi->jam_masuk,
            $absensi->jam_keluar ?? '-',
            ucfirst($absensi->status),
            $absensi->keterangan ?? '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 10);

                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'LAPORAN ABSENSI MAHASISWA');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->setCellValue('A3', 'Nama');
                $sheet->setCellValue('B3', $this->mahasiswa->name);
                $sheet->getStyle('A3')->getFont()->setBold(true);

                $sheet->setCellValue('A4', 'NIM');
                $sheet->setCellValue('B4', $this->mahasiswa->nim);
                $sheet->getStyle('A4')->getFont()->setBold(true);

                $sheet->setCellValue('A5', 'Jurusan');
                $sheet->setCellValue('B5', $this->mahasiswa->jurusan ?? '-');
                $sheet->getStyle('A5')->getFont()->setBold(true);

                $sheet->setCellValue('A6', 'Universitas');
                $sheet->setCellValue('B6', $this->mahasiswa->universitas ?? '-');
                $sheet->getStyle('A6')->getFont()->setBold(true);

                $sheet->setCellValue('D3', 'Total Hadir');
                $sheet->setCellValue('E3', $this->totalHadir . ' Hari');
                $sheet->getStyle('D3')->getFont()->setBold(true);

                $sheet->getStyle('A11:E11')->getFont()->setBold(true);
                $sheet->getStyle('A11:E11')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A11:E11')->getFill()->setFillType('solid')->getStartColor()->setRGB('E5E7EB');

                foreach (range('A', 'E') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
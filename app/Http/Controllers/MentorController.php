<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use App\Models\Magang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\AbsensiMahasiswaExport;
use App\Exports\RekapSemuaMahasiswaExport;
use Maatwebsite\Excel\Facades\Excel;

class MentorController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today()->toDateString();

        $mahasiswas = User::where('role', 'mahasiswa')
            ->with(['magang.absensi' => function ($query) use ($today) {
                $query->where('tanggal', $today);
            }])
            ->get();

        $hadirCount = 0;
        $belumPulangCount = 0;

        foreach ($mahasiswas as $mahasiswa) {
            if ($mahasiswa->magang && $mahasiswa->magang->absensi->isNotEmpty()) {
                $hadirCount++;
                $absensiHariIni = $mahasiswa->magang->absensi->first();
                if (is_null($absensiHariIni->jam_keluar)) {
                    $belumPulangCount++;
                }
            }
        }

        return view('mentor.dashboard', [
            'mahasiswas' => $mahasiswas,
            'today' => $today,
            'hadirCount' => $hadirCount,
            'belumPulangCount' => $belumPulangCount,
        ]);
    }

    public function showMahasiswa(User $mahasiswa)
    {
        $magang = $mahasiswa->magang()->with('absensi')->first();
        $semuaAbsensi = $magang ? $magang->absensi->sortByDesc('tanggal') : collect();

        $totalHadir = $semuaAbsensi->where('status', 'hadir')->count();
        $totalIzin = $semuaAbsensi->where('status', 'izin')->count();
        $totalSakit = $semuaAbsensi->where('status', 'sakit')->count();

        return view('mentor.show', compact('mahasiswa', 'semuaAbsensi', 'totalHadir', 'totalIzin', 'totalSakit'));
    }

    public function edit(User $mahasiswa)
    {
        return view('mentor.edit-mahasiswa', compact('mahasiswa'));
    }

    public function update(Request $request, User $mahasiswa)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:255', 'unique:users,nim,' . $mahasiswa->id],
            'jurusan' => ['nullable', 'string', 'max:255'],
            'universitas' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $mahasiswa->id],
        ]);

        $mahasiswa->update($validatedData);

        return redirect()->route('mentor.dashboard')->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    public function storeAbsensi(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
        ]);

        $mahasiswa = User::find($request->mahasiswa_id);
        $magang = $mahasiswa->magang;

        if (!$magang) {
            return response()->json(['success' => false, 'message' => 'Mahasiswa tidak memiliki data magang.'], 404);
        }

        Absensi::updateOrCreate(
            [
                'id_magang' => $magang->id,
                'tanggal'   => $request->tanggal,
            ],
            [
                'jam_masuk'   => $request->jam_masuk,
                'jam_keluar'  => $request->jam_keluar,
                'keterangan'  => $request->keterangan,
                'status'      => 'hadir'
            ]
        );

        return response()->json(['success' => true]);
    }

    public function exportMahasiswa(User $mahasiswa)
    {
        $magang = $mahasiswa->magang()->with('absensi')->first();
        $semuaAbsensi = $magang ? $magang->absensi->sortByDesc('tanggal') : collect();

        $totalHadir = $semuaAbsensi->where('status', 'hadir')->count();
        $totalIzin = $semuaAbsensi->where('status', 'izin')->count();
        $totalSakit = $semuaAbsensi->where('status', 'sakit')->count();

        $namaFile = 'laporan-absensi-' . Str::slug($mahasiswa->name) . '.xlsx';

        return Excel::download(new AbsensiMahasiswaExport($mahasiswa, $semuaAbsensi, $totalHadir, $totalIzin, $totalSakit), $namaFile);
    }

    public function exportRekapSemua()
    {
        $mahasiswas = User::where('role', 'mahasiswa')
            ->with('magang.absensi')
            ->get();

        $namaFile = 'rekap-total-absensi-mahasiswa-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new RekapSemuaMahasiswaExport($mahasiswas), $namaFile);
    }
}

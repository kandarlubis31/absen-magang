<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $magangUser = $user->magang;

        if (!$magangUser) {
            return view('dashboard', [
                'error' => 'Data magang Anda tidak ditemukan. Hubungi administrator.',
                'todayAttendance' => null,
                'allAttendances' => collect(),
            ]);
        }

        $todayAbsensi = $magangUser->absensi()
            ->where('tanggal', $today)
            ->first();

        $allAbsensi = $magangUser->absensi()
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('dashboard', [
            'todayAttendance' => $todayAbsensi,
            'allAttendances' => $allAbsensi,
        ]);
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();
        $magangUser = $user->magang;

        if (!$magangUser) {
            return redirect()->back()->with('error', 'Data magang tidak ditemukan.');
        }

        $alreadyCheckedIn = $magangUser->absensi()->where('tanggal', $today)->exists();

        if ($alreadyCheckedIn) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        Absensi::create([
            'id_magang' => $magangUser->id,
            'tanggal' => $today,
            'jam_masuk' => $now,
            'status' => 'hadir',
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan absen masuk.');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();
        $magangUser = $user->magang;

        if (!$magangUser) {
            return redirect()->back()->with('error', 'Data magang tidak ditemukan.');
        }

        $absensi = $magangUser->absensi()
            ->where('tanggal', $today)
            ->first();

        if (!$absensi || $absensi->jam_keluar) {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        $absensi->update([
            'jam_keluar' => $now,
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan absen pulang.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $updateData = $request->only('name', 'email');

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $updateData['profile_photo_path'] = $path;
        }

        $user->update($updateData);

        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('success', 'Password berhasil diubah!');
    }
}

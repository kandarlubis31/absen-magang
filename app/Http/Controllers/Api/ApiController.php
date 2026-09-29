<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Http\Resources\AbsensiResource;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'NIM atau password salah.'
            ], 401);
        }

        $user = User::where('nim', $credentials['nim'])->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function checkIn(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();
        $magangUser = $user->magang;

        if (!$magangUser) {
            return response()->json(['message' => 'Data magang tidak ditemukan.'], 404);
        }

        $alreadyCheckedIn = $magangUser->absensi()->where('tanggal', $today)->exists();

        if ($alreadyCheckedIn) {
            return response()->json(['message' => 'Anda sudah absen masuk hari ini.'], 422);
        }

        $absensi = Absensi::create([
            'id_magang' => $magangUser->id,
            'tanggal' => $today,
            'jam_masuk' => $now,
            'status' => 'hadir',
        ]);

        return response()->json([
            'message' => 'Berhasil absen masuk.',
            'data' => $absensi
        ], 201);
    }

    public function checkOut(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();
        $magangUser = $user->magang;

        if (!$magangUser) {
            return response()->json(['message' => 'Data magang tidak ditemukan.'], 404);
        }

        $absensi = $magangUser->absensi()->where('tanggal', $today)->first();

        if (!$absensi || $absensi->jam_keluar) {
            return response()->json(['message' => 'Aksi tidak valid.'], 422);
        }

        $absensi->update(['jam_keluar' => $now]);

        return response()->json([
            'message' => 'Berhasil absen pulang.',
            'data' => $absensi
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil.']);
    }
    public function statusHariIni(Request $request)
    {
        $user = $request->user();
        $magangUser = $user->magang;

        if (!$magangUser) {
            return response()->json(['message' => 'Data magang tidak ditemukan.'], 404);
        }

        $absensiHariIni = $magangUser->absensi()->where('tanggal', Carbon::today()->toDateString())->first();

        if ($absensiHariIni) {
            return new AbsensiResource($absensiHariIni);
        }

        return response()->json(['status' => 'belum_absen']);
    }
}

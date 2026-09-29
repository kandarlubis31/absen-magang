<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $todayAttendance = $user->attendances()
            ->where('date', $today)
            ->first();

        $allAttendances = $user->attendances()
            ->orderBy('date', 'desc')
            ->get();

        return view('dashboard', [
            'todayAttendance' => $todayAttendance,
            'allAttendances' => $allAttendances,
        ]);
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();

        $alreadyCheckedIn = $user->attendances()->where('date', $today)->exists();

        if ($alreadyCheckedIn) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => $now,
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan absen masuk.');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();

        $attendance = $user->attendances()
            ->where('date', $today)
            ->first();

        if (!$attendance || $attendance->check_out) {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        $attendance->update([
            'check_out' => $now,
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan absen pulang.');
    }
}
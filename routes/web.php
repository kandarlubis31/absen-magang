<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\MentorController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', \App\Http\Middleware\RedirectIfMentor::class])->group(function () {
    Route::get('/dashboard', [AbsensiController::class, 'index'])->name('dashboard');
    Route::post('/absensi/check-in', [AbsensiController::class, 'checkIn'])->name('absensi.checkin');
    Route::patch('/absensi/check-out', [AbsensiController::class, 'checkOut'])->name('absensi.checkout');
    Route::patch('/profile/update', [AbsensiController::class, 'updateProfile'])->name('profile.update.custom');
    Route::patch('/password/update', [AbsensiController::class, 'updatePassword'])->name('password.update.custom');
});

Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', [MentorController::class, 'dashboard'])->name('dashboard');
    Route::get('/mahasiswa/{mahasiswa}', [MentorController::class, 'showMahasiswa'])->name('mahasiswa.show');
    Route::get('/mahasiswa/{mahasiswa}/export', [MentorController::class, 'exportMahasiswa'])->name('mahasiswa.export');
    Route::get('/mahasiswa/{mahasiswa}/edit', [MentorController::class, 'edit'])->name('mahasiswa.edit');
    Route::patch('/mahasiswa/{mahasiswa}', [MentorController::class, 'update'])->name('mahasiswa.update');
    Route::get('/export-rekap-semua', [MentorController::class, 'exportRekapSemua'])->name('rekap.export');
    Route::post('/absensi/store', [MentorController::class, 'storeAbsensi'])->name('absensi.store');
});

require __DIR__ . '/../routes/auth.php';
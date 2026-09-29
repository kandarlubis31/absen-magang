<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login-custom');
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- MULAI LOGIKA PINTU MASUK ---
        // Cek peran user yang baru saja login
        if ($request->user()->role === 'mentor') {
            // Jika dia mentor, arahkan ke dashboard mentor
            return redirect()->intended(route('mentor.dashboard'));
        }

        // Jika bukan mentor (berarti mahasiswa), arahkan ke dashboard mahasiswa
        return redirect()->intended(route('dashboard'));
        // --- SELESAI LOGIKA PINTU MASUK ---
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

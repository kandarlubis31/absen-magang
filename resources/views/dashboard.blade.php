<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Dashboard Absensi Sistem Magang">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Dashboard Absensi - Sistem Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], },
                    colors: {
                        primary: { 50: '#f0f9ff', 100: '#e0f2fe', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1', 900: '#0c4a6e' },
                        secondary: { 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca' }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { transform: 'translateY(20px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
                        scaleIn: { '0%': { transform: 'scale(0.95)', opacity: '0' }, '100%': { transform: 'scale(1)', opacity: '1' } },
                        float: { '0%, 100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-10px)' } }
                    },
                }
            }
        }
    </script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea, #764ba2); }
        .card-hover { transition: all 0.3s ease; border: 1px solid transparent; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border-color: #0ea5e9; }
        .button-glow { box-shadow: 0 0 15px rgba(79, 70, 229, 0.5); }
        .table-row-hover { transition: background-color 0.2s ease; }
        .table-row-hover:hover { background-color: #f9fafb; }
        .dark .table-row-hover:hover { background-color: rgba(30, 41, 59, 0.8); }
        .toast { position: fixed; bottom: 20px; right: 20px; transform: translateX(calc(100% + 20px)); transition: transform 0.4s ease-in-out; z-index: 1000; }
        .toast.show { transform: translateX(0); }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 top-0 left-0 bg-blue-600 text-white px-4 py-2">Skip to main content</a>
    <div id="toast" class="toast bg-white dark:bg-slate-800 p-4 rounded-lg shadow-lg flex items-center max-w-sm">
        <div id="toast-icon" class="mr-3"><i class="fas fa-check-circle text-green-500 text-xl"></i></div>
        <div><p id="toast-message" class="text-sm font-medium">Operasi berhasil</p></div>
    </div>
    <header class="gradient-bg shadow-lg sticky top-0 z-40" role="banner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="animate-fade-in">
                    <h1 class="text-2xl font-bold text-white">Dashboard Absensi</h1>
                    <p class="text-indigo-100 text-sm mt-1">Selamat Datang, {{ Auth::user()->name }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="dark-mode-toggle" class="p-2 rounded-full text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white" aria-label="Toggle dark mode">
                        <i class="fas fa-moon block dark:hidden"></i><i class="fas fa-sun hidden dark:block"></i>
                    </button>
                    <div class="text-right text-white animate-fade-in hidden sm:block">
                        <div class="text-base font-semibold" id="currentTime" aria-live="polite"></div>
                        <div class="text-xs text-indigo-100" id="currentDate"></div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30" title="Logout" aria-label="Logout">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main id="main-content" class="py-8" role="main">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 card-hover h-full">
                        <div class="text-center mb-6">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto Profil" class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-white dark:border-slate-700 shadow-lg">
                            @else
                                <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-primary-500 to-secondary-600 text-white flex items-center justify-center text-4xl font-bold border-4 border-white dark:border-slate-700 shadow-lg">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <h3 class="text-xl font-bold mt-4">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->nim }}</p>
                        </div>
                        
                        <form action="{{ route('profile.update.custom') }}" method="POST" enctype="multipart/form-data" class="space-y-4 mb-8">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="name" class="block text-sm font-medium">Nama</label>
                                <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-slate-700 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-slate-700 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="profile_photo" class="block text-sm font-medium">Ganti Foto Profil</label>
                                <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="mt-1 block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/50 dark:file:text-primary-300">
                            </div>
                            <button type="submit" class="w-full bg-secondary-600 hover:bg-secondary-700 text-white font-semibold py-2 px-4 rounded-lg">Update Profil</button>
                        </form>
                        
                        <form action="{{ route('password.update.custom') }}" method="POST" class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                             @csrf
                             @method('PATCH')
                            <div>
                                <label for="current_password" class="block text-sm font-medium">Password Saat Ini</label>
                                <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-slate-700 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium">Password Baru</label>
                                <input type="password" name="password" id="password" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-slate-700 rounded-md shadow-sm">
                            </div>
                             <div>
                                <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-slate-700 rounded-md shadow-sm">
                            </div>
                            <button type="submit" class="w-full bg-gray-800 hover:bg-gray-700 dark:bg-slate-900 dark:hover:bg-black text-white font-semibold py-2 px-4 rounded-lg">Ubah Password</button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">
                    <div class="animate-slide-up" style="animation-delay: 0.1s;">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 card-hover">
                           @if (!$todayAttendance)
                            <div class="text-center">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center animate-float">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="text-2xl font-bold mb-3">Belum Absen Masuk</h3>
                                <p class="text-gray-600 dark:text-gray-300 mb-6">Silakan lakukan absen masuk untuk memulai aktivitas hari ini.</p>
                                <form method="POST" action="{{ route('absensi.checkin') }}">
                                    @csrf
                                    <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg button-glow transform hover:scale-105 transition-all duration-300">
                                        <div class="flex items-center space-x-3"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg><span>Absen Masuk</span></div>
                                    </button>
                                </form>
                            </div>
                            @elseif (!$todayAttendance->jam_keluar)
                            <div class="text-center">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center animate-float">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-3">Sudah Absen Masuk</h3>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">Anda masuk pada jam: <span class="font-semibold">{{ $todayAttendance->jam_masuk }}</span></p>
                                <p class="text-gray-500 dark:text-gray-400 mb-6">Jangan lupa untuk absen pulang setelah selesai bekerja.</p>
                                <form method="POST" action="{{ route('absensi.checkout') }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-semibold py-4 px-8 rounded-xl shadow-lg button-glow transform hover:scale-105 transition-all duration-300">
                                        <div class="flex items-center space-x-3"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg><span>Absen Pulang</span></div>
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="text-center">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center animate-float">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-3">Absen Hari Ini Selesai</h3>
                                <p class="text-gray-600 dark:text-gray-300 mb-6">Terima kasih atas kerja keras hari ini!</p>
                                <div class="bg-blue-50 dark:bg-slate-700 rounded-xl p-4">
                                    <div class="flex justify-between items-center text-sm">
                                        <div class="text-center">
                                            <p class="text-gray-500 dark:text-gray-400">Masuk</p><p class="font-semibold text-green-600 dark:text-green-400">{{ $todayAttendance->jam_masuk }}</p>
                                        </div>
                                        <div class="text-center"><svg class="w-6 h-6 text-gray-400 dark:text-gray-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg></div>
                                        <div class="text-center">
                                            <p class="text-gray-500 dark:text-gray-400">Pulang</p><p class="font-semibold text-red-600 dark:text-red-400">{{ $todayAttendance->jam_keluar }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="animate-slide-up" style="animation-delay: 0.3s;">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden card-hover">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-2xl font-bold">Riwayat Absensi</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full" aria-label="Riwayat Absensi">
                                    <thead class="bg-gray-50 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Absen Masuk</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Absen Pulang</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @forelse ($allAttendances as $attendance)
                                        <tr class="table-row-hover">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($attendance->tanggal)->translatedFormat('d F Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->jam_masuk }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->jam_keluar ?? 'Belum pulang' }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="3" class="px-6 py-12 text-center"><p class="text-gray-500 dark:text-gray-400">Belum ada riwayat absensi.</p></td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Dark mode
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const html = document.documentElement;
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }
        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        });

        // Live Clock
        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' };
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jakarta' };
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', timeOptions).replace(/\./g, ':');
            document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', dateOptions);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Toast Notification
        @if (session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if ($errors->any())
            showToast("{{ $errors->first() }}", 'error');
        @endif
        
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');
            toastMessage.textContent = message;
            if (type === 'error') {
                toastIcon.innerHTML = '<i class="fas fa-times-circle text-red-500 text-xl"></i>';
            } else {
                toastIcon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xl"></i>';
            }
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 4000);
        }
    </script>
</body>
</html>
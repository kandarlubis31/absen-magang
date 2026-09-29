<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
    <title>Login - Absensi Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .input-focus {
            transition: all 0.2s ease;
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen flex">
        <div class="hidden lg:block w-2/5 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=1932&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="h-full flex flex-col justify-end p-12 text-white relative z-10">
                <div class="animate-slide-up">
                    <h1 class="text-4xl font-bold mb-4 leading-tight">Selamat Datang Kembali!</h1>
                    <p class="text-lg text-white/80 mb-6 leading-relaxed">"Masuk untuk melanjutkan dan mencatat kehadiranmu hari ini. Semangat!"</p>
                </div>
            </div>
        </div>
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md animate-fade-in">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Masuk ke Akun</h2>
                    <p class="text-gray-600">Silakan masukkan kredensial Anda</p>
                </div>
                <div class="glass-card rounded-2xl p-8 shadow-xl">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                            <input id="nim" name="nim" type="text" required class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none bg-white" placeholder="Masukkan NIM kamu" value="{{ old('nim') }}" autofocus>
                            <x-input-error :messages="$errors->get('nim')" class="mt-2" />
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                            <input id="password" name="password" type="password" required class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none bg-white" placeholder="Password" autocomplete="current-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
                            </div>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">
                                Lupa password?
                            </a>
                            @endif
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Masuk</button>
                        <div class="text-center">
                            <p class="text-sm text-gray-600"> Belum punya akun? <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition-colors"> Daftar di sini </a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
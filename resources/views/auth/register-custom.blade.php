<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">
    <title>Register - Absensi Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out forwards',
                        'slide-up': 'slideUp 0.6s ease-out forwards',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
        <div class="hidden lg:block w-2/5 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?q=80&w=1974&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="h-full flex flex-col justify-end p-12 text-white relative z-10">
                <div class="animate-slide-up">
                    <h1 class="text-4xl font-bold mb-4 leading-tight"> Absensi Magang </h1>
                    <p class="text-lg text-white/80 mb-6 leading-relaxed"> "Daftar akun dulu bro!" </p>
                </div>
            </div>
        </div>
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md animate-fade-in">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Daftar Akun</h2>
                    <p class="text-gray-600">Lengkapi data untuk memulai</p>
                </div>
                <div class="glass-card rounded-2xl p-8 shadow-xl">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input id="name" name="name" type="text" required class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none bg-white" placeholder="Nama lengkap" value="{{ old('name') }}" autofocus autocomplete="name">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM</label>
                                <input id="nim" name="nim" type="text" required class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none bg-white" placeholder="NIM" value="{{ old('nim') }}" autocomplete="nim">
                                <x-input-error :messages="$errors->get('nim')" class="mt-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                                <input id="jurusan" name="jurusan" type="text" required class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none bg-white" placeholder="Jurusan" value="{{ old('jurusan') }}" autocomplete="jurusan">
                                <x-input-error :messages="$errors->get('jurusan')" class="mt-2" />
                            </div>
                            <div>
                                <label for="universitas" class="block text-sm font-medium text-gray-700 mb-2">Universitas</label>
                                <input id="universitas" name="universitas" type="text" required class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none bg-white" placeholder="Nama universitas" value="{{ old('universitas') }}" autocomplete="organization">
                                <x-input-error :messages="$errors->get('universitas')" class="mt-2" />
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input id="email" name="email" type="email" required class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none bg-white" placeholder="nama@email.com" value="{{ old('email') }}" autocomplete="username">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                                <input id="password" name="password" type="password" required class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none bg-white" placeholder="Password" autocomplete="new-password">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi</label>
                                <div class="relative">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required class="input-focus w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none bg-white" placeholder="Ulangi password" autocomplete="new-password">
                                    <div id="match-indicator" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-lg"></div>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"> Daftar Sekarang </button>
                        <div class="text-center">
                            <p class="text-sm text-gray-600"> Sudah punya akun? <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition-colors"> Masuk di sini </a> </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const matchIndicator = document.getElementById('match-indicator');

        function checkPasswordMatch() {
            const pwd = password.value;
            const confirmPwd = confirmPassword.value;
            if (confirmPwd.length === 0) {
                matchIndicator.textContent = '';
                return;
            }
            if (pwd === confirmPwd && pwd.length > 0) {
                matchIndicator.textContent = '✓';
                matchIndicator.className = 'absolute right-3 top-1/2 transform -translate-y-1/2 text-lg text-green-600';
            } else {
                matchIndicator.textContent = '✗';
                matchIndicator.className = 'absolute right-3 top-1/2 transform -translate-y-1/2 text-lg text-red-600';
            }
        }
        password.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>
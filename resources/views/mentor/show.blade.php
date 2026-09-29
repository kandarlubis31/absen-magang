<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Absensi - {{ $mahasiswa->name }}</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        {{-- Header Mahasiswa --}}
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('mentor.dashboard') }}" 
                       class="text-sm text-blue-600 hover:underline mb-2 block">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $mahasiswa->name }}</h1>
                    <p class="text-gray-500">{{ $mahasiswa->nim }} - {{ $mahasiswa->jurusan ?? 'Jurusan Belum Diisi' }}</p>
                </div>
                <div>
                    {{-- Export pakai form GET --}}
                    <form action="{{ route('mentor.mahasiswa.export', $mahasiswa->id) }}" method="GET">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent 
                                       rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                                       hover:bg-green-700 active:bg-green-900 focus:outline-none 
                                       focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 
                                       transition ease-in-out duration-150">
                            <i class="fas fa-file-excel mr-2"></i> Export ke Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Statistik Kehadiran --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow">
                <p class="font-bold">Total Hadir</p>
                <p class="text-3xl">{{ $totalHadir }} Hari</p>
            </div>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow">
                <p class="font-bold">Total Izin</p>
                <p class="text-3xl">{{ $totalIzin }} Hari</p>
            </div>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow">
                <p class="font-bold">Total Sakit</p>
                <p class="text-3xl">{{ $totalSakit }} Hari</p>
            </div>
        </div>

        {{-- Tabel Absensi --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($semuaAbsensi as $absensi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('l, d F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $absensi->jam_masuk }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $absensi->jam_keluar ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($absensi->status == 'hadir') bg-green-100 text-green-800
                                    @elseif($absensi->status == 'izin') bg-yellow-100 text-yellow-800
                                    @elseif($absensi->status == 'sakit') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($absensi->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Belum ada riwayat absensi untuk mahasiswa ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Mentor Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        {{-- Header --}}
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-blue-500 rounded-lg p-2 mr-3">
                        <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Dashboard Mentor</h1>
                        <p class="text-sm text-gray-500">Monitoring Magang Mahasiswa</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:block">
                        <p class="text-sm text-gray-600">Hari ini: <span class="font-medium">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</span></p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Main --}}
        <main class="flex-grow py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('info'))
                <div class="mb-6 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md" role="alert">
                    <p>{{ session('info') }}</p>
                </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6 card-hover">
                        <div class="flex items-center">
                            <div class="rounded-full bg-blue-100 p-3 mr-4"><i class="fas fa-users text-blue-600 text-xl"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Mahasiswa</p>
                                <h2 class="text-2xl font-bold text-gray-900">{{ count($mahasiswas) }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 card-hover">
                        <div class="flex items-center">
                            <div class="rounded-full bg-green-100 p-3 mr-4"><i class="fas fa-user-check text-green-600 text-xl"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Hadir Hari Ini</p>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $hadirCount }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 card-hover">
                        <div class="flex items-center">
                            <div class="rounded-full bg-yellow-100 p-3 mr-4"><i class="fas fa-user-clock text-yellow-600 text-xl"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Belum Pulang</p>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $belumPulangCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">Rekap Kehadiran Hari Ini</h2>
                            <p class="mt-1 text-sm text-gray-500">Daftar mahasiswa magang dan status kehadiran mereka hari ini</p>
                        </div>
                        <div>
                            <a href="{{ route('mentor.rekap.export') }}" id="btn-export-rekap" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                <i class="fas fa-print mr-2"></i>Print Laporan Total
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($mahasiswas as $mahasiswa)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0"><span class="font-medium text-blue-700">{{ substr($mahasiswa->name, 0, 1) }}</span></div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $mahasiswa->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $mahasiswa->jurusan ?? 'Jurusan Belum Diisi' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $mahasiswa->nim }}</td>
                                    @php $absensiHariIni = $mahasiswa->magang ? $mahasiswa->magang->absensi->first() : null; @endphp
                                    @if ($absensiHariIni)
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $absensiHariIni->jam_masuk }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $absensiHariIni->jam_keluar ?? '-' }}</td>
                                    <td class="px-6 py-4"><span class="status-badge {{ $absensiHariIni->jam_keluar ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">{{ $absensiHariIni->jam_keluar ? 'Selesai' : 'Sedang Magang' }}</span></td>
                                    @else
                                    <td class="px-6 py-4 text-sm text-gray-500">-</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">-</td>
                                    <td class="px-6 py-4"><span class="status-badge bg-red-100 text-red-800">Belum Hadir</span></td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('mentor.mahasiswa.show', $mahasiswa->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('mentor.mahasiswa.edit', $mahasiswa->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Edit Data"><i class="fas fa-edit"></i></a>
                                        <button class="text-green-600 hover:text-green-900" title="Input Kehadiran Manual" onclick="showAbsensiModal({{ $mahasiswa->id }}, '{{ $mahasiswa->name }}')"><i class="fas fa-user-check"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <div class="py-8"><i class="fas fa-inbox text-gray-300 text-4xl mb-2"></i>
                                            <p>Tidak ada data mahasiswa.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <footer class="bg-white mt-auto py-4 border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                <p>© {{ date('Y') }} Dashboard Mentor Magang. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <div id="absensi-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Input Kehadiran Manual</h3>
                    <button onclick="closeAbsensiModal()" class="text-gray-400 hover:text-gray-500"><i class="fas fa-times"></i></button>
                </div>
                <form id="absensi-form" action="{{ route('mentor.absensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="mahasiswa-id" name="mahasiswa_id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mahasiswa</label>
                        <input type="text" id="mahasiswa-name" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Keluar</label>
                        <input type="time" name="jam_keluar" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika mahasiswa belum pulang.</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeAbsensiModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAbsensiModal(mahasiswaId, mahasiswaName) {
            document.getElementById('mahasiswa-id').value = mahasiswaId;
            document.getElementById('mahasiswa-name').value = mahasiswaName;
            document.getElementById('absensi-modal').classList.remove('hidden');
        }

        function closeAbsensiModal() {
            document.getElementById('absensi-modal').classList.add('hidden');
            document.getElementById('absensi-form').reset();
        }

        document.getElementById('absensi-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = this.action;

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Terjadi kesalahan: ' + (data.message || 'Gagal menyimpan data.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan koneksi saat menyimpan data.');
                });
        });
    </script>
</body>

</html>
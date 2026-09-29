# absen-magang

Aplikasi **absensi magang** berbasis Laravel dengan panel khusus mentor & API mobile.

## Fitur Utama
- Check-in / check-out absensi harian (web + API mobile)
- Dashboard peserta magang (mahasiswa) dengan profil & ganti password
- Panel mentor: pantau mahasiswa, edit data, export rekap Excel (maatwebsite/excel)
- Middleware `RedirectIfMentor` & `role:mentor` untuk pemisahan role
- API Sanctum untuk aplikasi mobile (`absensimagang-mobile/`)

## Tech Stack
Laravel · Sanctum · maatwebsite/excel · Tailwind + Vite · Bootstrap

## Menjalankan
```bash
composer install
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

Lihat `CONTEXT.md` di folder ini untuk detail arsitektur.

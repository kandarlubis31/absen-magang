# absen-magang — Project Context

## Ringkasan
Aplikasi **absensi magang (internship attendance)** berbasis Laravel. Mahasiswa magang bisa check-in/check-out harian, sementara **mentor** memantau mahasiswa, mengelola data, dan mengekspor rekap absensi ke Excel. Tersedia juga **API mobile** (Sanctum) yang dipakai oleh aplikasi `absensimagang-mobile/` di folder ini.

## Tech Stack
- Laravel (framework standar, auth Breeze-style)
- Sanctum (API token auth)
- maatwebsite/excel (export laporan)
- Tailwind + Vite (view belum diverifikasi detail)  
- Carbon untuk manipulasi tanggal

## Struktur Utama
```
app/
├── Exports/
│   ├── AbsensiMahasiswaExport.php        # Excel per mahasiswa (header info + tabel)
│   └── RekapSemuaMahasiswaExport.php     # Excel rekap semua mahasiswa
├── Http/Controllers/
│   ├── AbsensiController.php             # WEB: dashboard, check-in/out, update profil & password
│   ├── MentorController.php              # WEB: panel mentor (pantau, edit, export, store absensi)
│   ├── Api/ApiController.php             # API: login (NIM), check-in/out, statusHariIni
│   ├── AttendanceController.php          # ⚠️ Legacy/duplicate (pakai model Attendance, tidak dipakai route)
│   └── Auth/                             # Breeze auth controllers
├── Http/Middleware/
│   ├── RedirectIfMentor.php              # mentor → redirect ke mentor.dashboard
│   └── CheckRole.php                     # role:mentor guard
├── Http/Resources/AbsensiResource.php    # format JSON absensi untuk API
└── Models/
    ├── User.php     # fillable: nim, jurusan, universitas, email, dll; relasi magang()
    ├── Magang.php   # id_mahasiswa, mulai/selesai magang, status aktif/selesai
    └── Absensi.php  # id_magang, tanggal, jam_masuk, jam_keluar, status, keterangan
routes/
├── web.php    # dashboard + absensi + profile + panel mentor
├── api.php    # login NIM + check-in/out (Sanctum)
└── auth.php   # Breeze auth (register, login, forgot/reset password, verify email)
absensimagang-mobile/   # aplikasi mobile client API
```

## Skema Database
- **users**: + nim (unique), jurusan, universitas, no_hp, alamat, role (default `mahasiswa`)
- **magangs**: id_mahasiswa (FK users, cascade), mulai_magang, selesai_magang, status (`aktif`/`selesai`)
- **absensis**: id_magang (FK magangs, cascade), tanggal, jam_masuk, jam_keluar (nullable), status (`hadir`/`izin`/`sakit`/`alfa`), keterangan

## Role & Alur
- **mahasiswa**: dashboard → check-in (1x/hari, status `hadir`), check-out (isi jam_keluar)
- **mentor**: dashboard statistik hari ini (hadir & belum pulang), lihat/edit data mahasiswa, export Excel per mahasiswa & rekap semua, input absensi manual via AJAX (`storeAbsensi`)
- API login pakai **NIM + password** (bukan email)

## Rules Bisnis
| Rule | Implementasi |
|------|-------------|
| 1x check-in per hari | `absensi()->where('tanggal', today)->exists()` → tolak |
| Check-out hanya setelah check-in | `!$absensi || $absensi->jam_keluar` → 'Aksi tidak valid' |
| Mentor tidak akses dashboard mahasiswa | `RedirectIfMentor` middleware |
| Role guard | `CheckRole` + `role:mentor` di route group mentor |
| API 404 kalau data magang tidak ada | `magangUser` null → 404 |
| Export per mahasiswa | header Excel: nama/NIM/jurusan/universitas + total hadir |

## API Endpoints (Sanctum)
| Method | URI | Keterangan |
|--------|-----|------------|
| POST | `/api/login` | body: nim, password → access_token (Bearer) |
| GET | `/api/user` | profil user |
| POST | `/api/check-in` | absen masuk hari ini (201) |
| POST | `/api/check-out` | absen pulang |
| POST | `/api/logout` | hapus currentAccessToken |
| GET | `/api/absensi/status-hari-ini` | AbsensiResource / `{status: belum_absen}` |

## Catatan
- `AttendanceController` + model `Attendance` adalah sisa versi lama — **tidak terdaftar di routes** (jangan dihapus tanpa cek dulu, tapi jangan dipakai).
- Export Excel pakai `Excel::download()` dengan `FromCollection, WithHeadings, WithMapping, WithEvents`.
- `register` Breeze tetap ada tapi data magang biasanya disiapkan admin/mentor.
- `routes/auth.php` punya route verify-email, tapi model `User` TIDAK implement `MustVerifyEmail` — jadi verifikasi email tidak benar-benar ditegakkan.

---
*Last updated: 2026-08-08 · Ditulis manual dari kode asli*

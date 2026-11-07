# absen-magang

Attendance system for interns: a Laravel web app with a mentor panel, plus a Sanctum-authenticated
API consumed by a companion Expo mobile app in `absensimagang-mobile/`.

## What it covers

- **Daily check-in / check-out** for interns, available both from the web app and from the mobile
 client through the same API.
- **Intern dashboard** — profile and password change.
- **Mentor panel** — monitor assigned interns, correct their records, and export a recap to Excel.
- **Role separation** enforced by middleware, not by hiding buttons: `RedirectIfMentor` and the
 `role:mentor` guard decide what a session is allowed to reach.
- **Admin-facing web controllers** for the mentor side of the workflow.

## Stack

Laravel · Sanctum (token auth) · maatwebsite/excel · Blade · Tailwind + Vite · Bootstrap ·
Expo / React Native (mobile)

## Structure

```
app/Http/Controllers/Api/        mobile API (auth, attendance)
app/Http/Controllers/Web/        mentor-facing pages
app/Http/Middleware/             role guards
absensimagang-mobile/            Expo app (expo-router: (auth), (app), (tabs))
```

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

The mobile client points at the same API base URL; set it in the Expo app's configuration before
running it against your instance.

## Mobile client

`absensimagang-mobile/` is an Expo project using file-based routing, with an `(auth)` group for
login and an `(app)` group for the authenticated tabs. It authenticates with a Sanctum token and
uses the same endpoints as the web app.

## Notes

- Seeders create the demo accounts and sample attendance rows; do not run `migrate:fresh --seed`
 against data you care about.
- Excel export is handled by `maatwebsite/excel`, so large exports are streamed rather than held
 in memory.
- Internship records are personal data. If you deploy this, restrict access, and do not commit a
 populated `.env` or database dump.

---

## Bahasa Indonesia

**absen-magang** adalah sistem absensi peserta magang: aplikasi web Laravel dengan panel mentor, plus API ber-autentikasi Sanctum yang dipakai aplikasi mobile Expo pendamping di `absensimagang-mobile/`.

---

## License

MIT

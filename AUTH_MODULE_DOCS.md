# Nexus Rental Auth Module Documentation

## Fitur Auth
Modul otentikasi sederhana untuk sistem Nexus Rental. Dibangun menggunakan CodeIgniter 4 dan Bootstrap 4.6 (Lokal).

## Komponen

### Database
Database `pemweb` dibuat melalui migrasi:
`php spark migrate`

Struktur tabel utama:
- `users`
- `pelanggan`
- `unit_ps`
- `reservasi`
- `pembayaran`

Struktur tabel `users`:
- `id` (Primary Key, Auto Increment)
- `nama` (VARCHAR 100)
- `email` (VARCHAR 100, Unique)
- `password` (VARCHAR 255, Hashed)
- `no_hp` (VARCHAR 20, Nullable)
- `role` (ENUM: 'admin', 'pelanggan', Default: 'pelanggan')
- `created_at` (DATETIME)

### MVC (HMVC Pattern)
1. **Model**: `Modules\Login\Models\User`
   - Allowed fields: `nama`, `email`, `password`, `no_hp`, `role`.
   - Menggunakan callback `beforeInsert` dan `beforeUpdate` untuk auto-hashing password (dengan `password_hash`).

2. **Controller**: `Modules\Login\Controllers\Login`
   - Mengatur validasi register dan login.
   - Login menggunakan email dan password.
   - Mengatur session data (`user_id`, `nama`, `role`, `logged_in`).

3. **Views**:
   - Diletakkan di `app/Modules/Login/Views/`
   - `login.php`
   - `register.php`
   - Keduanya menggunakan template layout yang menggunakan aset vendor CSS/JS lokal (Bootstrap 4.6, jQuery, Popper).

### Routing
Route di-update pada `app/Config/Routes.php`:
- `GET login` => `\Modules\Login\Controllers\Login::index`
- `POST login/process` => `\Modules\Login\Controllers\Login::processLogin`
- `GET register` => `\Modules\Login\Controllers\Login::register`
- `POST register/process` => `\Modules\Login\Controllers\Login::processRegister`
- `GET logout` => `\Modules\Login\Controllers\Login::logout`

## Aset Vendor
Penting: File statis seperti Bootstrap, jQuery, dan Popper diletakkan di `public/vendor/`. Folder tersebut dikecualikan dari `vendor/` default ignore dengan modifikasi `.gitignore` (`!public/vendor/`).

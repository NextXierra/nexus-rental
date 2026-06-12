# Royal Rental Auth Module Documentation

## Fitur Auth
Modul otentikasi sederhana untuk sistem Royal Rental. Dibangun di dalam direktori `ci4/` menggunakan CodeIgniter 4 dan Bootstrap 4.6 (Lokal).

## Komponen

### Database
Tabel `users` dibuat melalui migrasi:
`php spark migrate`

Struktur tabel:
- `id` (Primary Key, Auto Increment)
- `username` (VARCHAR 100, Unique)
- `email` (VARCHAR 255, Unique)
- `password` (VARCHAR 255, Hashed)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

### MVC (HMVC Pattern)
1. **Model**: `Modules\Login\Models\User`
   - Allowed fields: `username`, `email`, `password`.
   - Menggunakan callback `beforeInsert` dan `beforeUpdate` untuk auto-hashing password (dengan `password_hash`).

2. **Controller**: `Modules\Login\Controllers\Login`
   - Mengatur validasi register dan login.
   - Mengatur session data (`user_id`, `username`, `logged_in`).

3. **Views**:
   - Diletakkan di `app/Modules/Login/Views/`
   - `login.php`
   - `register.php`
   - Keduanya menggunakan template layout yang menggunakan aset vendor CSS/JS lokal (Bootstrap 4.6, jQuery, Popper).

### Routing
Route di-update pada `ci4/app/Config/Routes.php`:
- `GET login` => `\Modules\Login\Controllers\Login::index`
- `POST login/process` => `\Modules\Login\Controllers\Login::processLogin`
- `GET register` => `\Modules\Login\Controllers\Login::register`
- `POST register/process` => `\Modules\Login\Controllers\Login::processRegister`
- `GET logout` => `\Modules\Login\Controllers\Login::logout`

## Aset Vendor
Penting: File statis seperti Bootstrap, jQuery, dan Popper diletakkan di `public/vendor/`. Folder tersebut dikecualikan dari `vendor/` default ignore dengan modifikasi `.gitignore` (`!public/vendor/`).

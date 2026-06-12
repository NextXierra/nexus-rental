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

### MVC
1. **Model**: `App\Models\User`
   - Allowed fields: `username`, `email`, `password`.
   - Menggunakan callback `beforeInsert` dan `beforeUpdate` untuk auto-hashing password (dengan `password_hash`).

2. **Controller**: `App\Controllers\Login`
   - Mengatur validasi register dan login.
   - Mengatur session data (`user_id`, `username`, `logged_in`).

3. **Views**:
   - `auth/login.php`
   - `auth/register.php`
   - Keduanya menggunakan template layout yang menggunakan aset vendor CSS/JS lokal (Bootstrap 4.6, jQuery, Popper).

### Routing
Route di-update pada `ci4/app/Config/Routes.php`:
- `GET login` => `Login::index`
- `POST login/process` => `Login::processLogin`
- `GET register` => `Login::register`
- `POST register/process` => `Login::processRegister`
- `GET logout` => `Login::logout`

## Aset Vendor
Penting: File statis seperti Bootstrap, jQuery, dan Popper diletakkan di `public/vendor/`. Folder tersebut dikecualikan dari `vendor/` default ignore dengan modifikasi `.gitignore` (`!public/vendor/`).

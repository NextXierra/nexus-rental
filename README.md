# Nexus Rental - Playstation Rental Management System

Aplikasi manajemen rental Playstation berbasis web menggunakan PHP framework CodeIgniter 4 dengan arsitektur Modular custom.

## Stack Teknologi
- **Backend:** CodeIgniter 4 (PHP 8.2+)
- **Database:** MariaDB / MySQL
- **Frontend:** Bootstrap 4.6 (Local), Font Awesome 4.7.0 (Local), Custom CSS (Playstation Dark Theme)

## Database Configuration
Konfigurasi database di `.env`:
- Host: `43.157.228.10`
- Port: `3307`
- Database: `pemweb`
- User: `admin`
- Password: `db_password`

### Setup Database & Seeds
Jalankan perintah berikut untuk migrasi dan mengisi data awal:
```bash
php spark migrate
php spark db:seed UserSeeder
php spark db:seed UnitPsSeeder
php spark db:seed PelangganSeeder
```

## Struktur Modul (`app/Modules/`)
- **LandingPage**: Tampilan utama web ketersediaan unit.
- **Login**: Autentikasi akun (Login, Register, Logout, Model User).
- **DashboardAdmin**: Area admin untuk mengelola unit, reservasi, dan pembayaran.
- **DashboardUser**: Area pelanggan (member) untuk melihat profil dan mengajukan reservasi.

## Fitur Utama & Alur Kerja

### 1. Autentikasi & Guard
- Login menggunakan email dan password.
- Session menyimpan `user_id`, `nama`, `role`, dan `logged_in`.
- Hak akses dibatasi lewat controller (admin/pelanggan). Akses tidak sah akan dilempar ke halaman login.

### 2. Unit PS (Admin)
- CRUD data unit Playstation (PS4 / PS5).
- Pengaturan harga sewa per jam dan status (`tersedia`, `disewa`, `maintenance`).

### 3. Reservasi (Admin & User)
- **Reservasi Admin:** Input reservasi offline (non-member) langsung ketik nama, atau online (member) via dropdown.
- **Reservasi User:** Member dapat mengajukan booking dari dashboard mereka (status `pending`).
- **Validasi Overlap (Live Check):** Sistem secara dinamis mengecek tabrakan jadwal menggunakan fetch API `/check-units` dan `/check-availability`. Dropdown unit akan otomatis menonaktifkan (`disabled`) unit yang sudah disewa pada jam tersebut.
- **Waktu & Durasi:** Menggunakan input tanggal (`date`) dan drop down jam/menit terpisah untuk kemudahan pengisian.
- **Approval Admin:** Permintaan pending dari user muncul paling atas. Admin dapat menyetujui (mengubah status reservasi jadi `aktif`, unit jadi `disewa`, pembayaran jadi `lunas`) atau menolak (status `dibatalkan`).

### 4. Pembayaran
- Pembayaran terbuat otomatis saat reservasi disimpan.
- Metode **QRIS**: Status awal `sudah_bayar`.
- Metode **Tunai**: Status awal `belum_bayar`.
- Setelah disetujui (ACC) admin, status otomatis berganti menjadi `lunas`.

## Struktur Branch Git
Semua branch dikembangkan mandiri dari branch `main`:
- `main`: Branch utama produksi.
- `admin-home-dashboard`: Menu dashboard admin.
- `admin-pelanggan`: Pengelolaan pelanggan admin.
- `admin-laporan`: Laporan pendapatan.
- `user-home`: Dashboard home user.
- `user-profil`: Profil user.

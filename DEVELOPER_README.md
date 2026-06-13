# Developer Guide & Next Steps

Panduan untuk developer selanjutnya agar konsisten dengan struktur, gaya desain, dan arsitektur yang sudah dibangun.

---

## 1. Arsitektur & Struktur Kode

Project ini menggunakan **CodeIgniter 4** dengan pola **Modular (HMVC)**. Semua fitur dibagi berdasarkan domain di folder `app/Modules/`:

- **`LandingPage`**: Halaman depan utama.
- **`Login`**: Registrasi, login, dan logout.
- **`DashboardAdmin`**: Dashboard khusus role `admin`.
- **`DashboardUser`**: Dashboard khusus role `pelanggan`.

### Aturan Penamaan (Naming Conventions)
Untuk menjaga konsistensi dan simetrisasi struktur file:

- **Controller**: Wajib menggunakan PascalCase dan diakhiri dengan suffix `Controller` (contoh: `DashboardController.php`, `ReservationController.php`).
- **Model**: Wajib menggunakan PascalCase dan diakhiri dengan suffix `Model` (contoh: `UserModel.php`, `ReservationModel.php`).
- **Views**: Menggunakan bahasa Inggris, lowercase/snake_case (contoh: `dashboard.php`, `reservation.php`, `unit_ps.php`).
- **Symmetrical Structure**: Struktur view dan controller di `DashboardAdmin` dan `DashboardUser` harus disamakan namanya untuk mempermudah maintenance (contoh: keduanya memiliki `DashboardController` dan view `reservation.php`).

---

## 2. Sistem Desain (Doodle-Brutalism)

Tema visual menggunakan **Doodle-Brutalism** ("Royal Scribble") yang mengkombinasikan grid brutalist kontras tinggi dengan sentuhan dekorasi hand-drawn.

### Aset Lokal (Offline First)
- **Bootstrap 4.6** & **Font Awesome 4.7**: Dikelola secara lokal di `public/vendor/`.
- **Font**: Semua font dimuat secara lokal di `public/assets/fonts/` (tidak memakai Google Fonts API eksternal):
  - **`Caveat`**: Untuk headline, judul halaman, logo.
  - **`Hanken Grotesk`**: Untuk spec detail, teks body, label form.

### CSS Variables & Classes (`variables.css`)
- **Canvas / Background**: `#F6F2E9` (`var(--background)` / `var(--surface)`).
- **Ink / Text / Borders**: `#1c1b1b` (`var(--primary)`).
- **Highlighters / Accents**:
  - Burnt Orange (`#d95d39`): Tombol utama, harga, highlight penting.
  - Turquoise (`#2ec4b6`): Aksen sekunder, select field, menu aktif.
  - Available Green (`#70a049`): Status tersedia.
  - Occupied Red (`#c23b22`): Status disewa/batal.
- **Borders & Shadows**:
  - Border kasar 2px hitam (`var(--border-rough)`).
  - Block shadow solid hitam (`box-shadow: 4px 4px 0px 0px #000;`).
  - Efek hover bergeser 2px (`transform: translate(-2px, -2px); box-shadow: 6px 6px 0px 0px #000;`).

---

## 3. Daftar Tugas Selanjutnya (Todo List)

Developer berikutnya disarankan melanjutkan fitur-fitur berikut yang masih kosong/berupa mockup:

### A. Integrasi Halaman Depan Dinamis
- [ ] Ubah data `availability` di `HomeController::index` agar mengambil real-time status dari database `unit_ps` (saat ini masih data dummy).
- [ ] Update status unit secara otomatis berdasarkan jam mulai/selesai reservasi yang aktif.

### B. Fitur Admin Dashboard
- [ ] **Laporan Pendapatan**: Hubungkan ke menu "Laporan". Tampilkan grafik pendapatan harian/bulanan berdasarkan data pembayaran.
- [ ] **Kelola Pelanggan**: Implementasikan CRUD pelanggan untuk mengelola data pelanggan offline dan online.

### C. Fitur User Dashboard
- [ ] **Edit Profil**: Buat halaman manajemen profil agar user bisa mengubah nama, nomor HP, email, dan password mereka sendiri.
- [ ] **Upload Bukti Bayar / QRIS**: Tambahkan fungsionalitas upload bukti bayar pada reservasi yang menggunakan metode QRIS.

### D. Notifikasi & Integrasi
- [ ] Hubungkan form kritik/saran ke database.
- [ ] Sediakan tombol redirect otomatis ke WhatsApp admin setelah user selesai mengajukan booking online.
